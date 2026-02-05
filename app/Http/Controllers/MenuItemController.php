<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:header_setup'])->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'importFromHeader']);
    }

    /**
     * One-time bootstrap: import top-level menu items from Header Settings only when Menu Items is empty.
     *
     * Once Menu Items exists, it becomes the source of truth (so admin CRUD changes won't be recreated
     * from old header_menu_labels/header_menu_links settings).
     */
    private function syncFromHeaderSettings(): void
    {
        // If Menu Items already has any top-level items, don't auto-create/update from settings anymore.
        if (MenuItem::whereNull('parent_id')->exists()) {
            return;
        }

        $labels = get_setting('header_menu_labels') ? json_decode(get_setting('header_menu_labels'), true) : [];
        $links  = get_setting('header_menu_links') ? json_decode(get_setting('header_menu_links'), true) : [];
        if (empty($labels) || !is_array($labels)) {
            return;
        }

        $order = 100;
        foreach ($labels as $i => $label) {
            $link = isset($links[$i]) ? (string) $links[$i] : '#';
            MenuItem::create([
                'label'      => $label,
                'link'       => $link,
                'sort_order' => $order,
                'parent_id'  => null,
            ]);
            $order--;
        }
    }

    /**
     * Display a listing of menu items (header nav).
     */
    public function index(Request $request)
    {
        $this->syncFromHeaderSettings();

        $sort_search = $request->get('search');

        // Build a full tree so children + grandchildren are visible.
        $roots = MenuItem::whereNull('parent_id')
            ->with('childrenWithNested')
            ->orderBy('sort_order', 'desc')
            ->orderBy('id')
            ->get();

        $menu_items = $this->flattenMenuTreeForIndex($roots, $sort_search);

        return view('backend.menu_items.index', [
            'menu_items'  => $menu_items, // flat list: [ ['item'=>MenuItem,'depth'=>int,'matches'=>bool], ... ]
            'sort_search' => $sort_search,
        ]);
    }

    /**
     * Show the form for creating a new menu item.
     * Optional query: parent_id to pre-select parent (e.g. "Add child" from index).
     */
    public function create(Request $request)
    {
        $selected_parent_id = $request->get('parent_id');
        $selected_parent = null;
        if ($selected_parent_id) {
            $selected_parent = MenuItem::find((int) $selected_parent_id);
            if (!$selected_parent) {
                $selected_parent_id = null;
            }
        }

        $parents = $this->flattenMenuTreeForSelect(
            MenuItem::whereNull('parent_id')
                ->with('childrenWithNested')
                ->orderBy('sort_order', 'desc')
                ->orderBy('id')
                ->get()
        );

        return view('backend.menu_items.create', compact('parents', 'selected_parent_id', 'selected_parent'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label'     => 'required|string|max:255',
            'link'      => 'nullable|string|max:500',
            'sort_order'=> 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item = new MenuItem();
        $item->label = $request->label;
        $item->link = $request->link ?: '#';
        $item->sort_order = (int) ($request->sort_order ?? 0);
        $item->parent_id = $request->parent_id ?: null;
        $item->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => translate('Menu item has been created successfully'),
                'redirect' => route('menu-items.index'),
            ]);
        }
        return redirect()->route('menu-items.index')->with('success', translate('Menu item has been created successfully'));
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit($id)
    {
        $menu_item = MenuItem::with('parent', 'childrenWithNested')->findOrFail($id);

        $excludeIds = array_merge([$menu_item->id], $this->collectDescendantIds($menu_item));

        $parents = $this->flattenMenuTreeForSelect(
            MenuItem::whereNull('parent_id')
                ->with('childrenWithNested')
                ->orderBy('sort_order', 'desc')
                ->orderBy('id')
                ->get(),
            $excludeIds
        );

        return view('backend.menu_items.edit', compact('menu_item', 'parents'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'label'     => 'required|string|max:255',
            'link'      => 'nullable|string|max:500',
            'sort_order'=> 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item = MenuItem::findOrFail($id);
        $parentId = $request->parent_id ?: null;
        if ($parentId == $id) {
            $parentId = $item->parent_id;
        }
        if ($parentId) {
            $ancestor = MenuItem::find($parentId);
            while ($ancestor) {
                if ($ancestor->id == $id) {
                    return redirect()->back()->withErrors(['parent_id' => translate('Parent cannot be self or a descendant.')]);
                }
                $ancestor = $ancestor->parent;
            }
        }
        $item->label = $request->label;
        $item->link = $request->link ?: '#';
        $item->sort_order = (int) ($request->sort_order ?? 0);
        $item->parent_id = $parentId;
        $item->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => translate('Menu item has been updated successfully'),
                'redirect' => route('menu-items.index'),
            ]);
        }
        return redirect()->route('menu-items.index')->with('success', translate('Menu item has been updated successfully'));
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        foreach ($item->children as $child) {
            $child->parent_id = $item->parent_id;
            $child->save();
        }
        $item->delete();
        return redirect()->route('menu-items.index')->with('success', translate('Menu item has been deleted successfully'));
    }

    /**
     * Import current header menu (from settings) into menu_items.
     */
    public function importFromHeader(Request $request)
    {
        $labels = get_setting('header_menu_labels') ? json_decode(get_setting('header_menu_labels'), true) : [];
        $links  = get_setting('header_menu_links') ? json_decode(get_setting('header_menu_links'), true) : [];

        if (empty($labels)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => translate('No header menu items to import.')], 422);
            }
            return redirect()->route('menu-items.index')->with('warning', translate('No header menu items to import.'));
        }

        $order = 100;
        foreach ($labels as $i => $label) {
            MenuItem::create([
                'label'      => $label,
                'link'       => $links[$i] ?? '#',
                'sort_order' => $order--,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => translate('Header menu imported successfully.'),
                'redirect' => route('menu-items.index'),
            ]);
        }
        return redirect()->route('menu-items.index')->with('success', translate('Header menu imported successfully.'));
    }

    /**
     * Flatten a menu tree for the index table (keeps hierarchy, supports search).
     *
     * @param  \Illuminate\Support\Collection  $roots
     * @param  string|null  $term
     * @return array<int, array{item: MenuItem, depth: int, matches: bool}>
     */
    private function flattenMenuTreeForIndex($roots, ?string $term): array
    {
        $needle = $term ? mb_strtolower(trim($term)) : null;
        return $this->buildIndexRows($roots, 0, $needle);
    }

    private function buildIndexRows($nodes, int $depth, ?string $needle): array
    {
        $rows = [];
        foreach ($nodes as $node) {
            $label = (string) ($node->label ?? '');
            $link  = (string) ($node->link ?? '');
            $matches = $needle ? (mb_stripos($label, $needle) !== false || mb_stripos($link, $needle) !== false) : true;

            $children = $node->childrenWithNested ?? collect();
            $childRows = ($children && $children->count() > 0) ? $this->buildIndexRows($children, $depth + 1, $needle) : [];

            $includeNode = $needle ? ($matches || count($childRows) > 0) : true;
            if ($includeNode) {
                $rows[] = ['item' => $node, 'depth' => $depth, 'matches' => (bool) $matches];
                foreach ($childRows as $row) {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    /**
     * Flatten tree for a <select> list (with depth), optionally excluding IDs.
     *
     * @param  \Illuminate\Support\Collection  $roots
     * @param  array<int,int>  $excludeIds
     * @return array<int, array{item: MenuItem, depth: int}>
     */
    private function flattenMenuTreeForSelect($roots, array $excludeIds = []): array
    {
        $exclude = array_fill_keys(array_map('intval', $excludeIds), true);
        $out = [];
        $this->flattenMenuTreeForSelectRecursive($roots, 0, $exclude, $out);
        return $out;
    }

    private function flattenMenuTreeForSelectRecursive($nodes, int $depth, array $exclude, array &$out): void
    {
        foreach ($nodes as $node) {
            if (!isset($exclude[(int) $node->id])) {
                $out[] = ['item' => $node, 'depth' => $depth];
            }
            $children = $node->childrenWithNested ?? collect();
            if ($children && $children->count() > 0) {
                $this->flattenMenuTreeForSelectRecursive($children, $depth + 1, $exclude, $out);
            }
        }
    }

    private function collectDescendantIds(MenuItem $item): array
    {
        $ids = [];
        $children = $item->childrenWithNested ?? collect();
        foreach ($children as $child) {
            $ids[] = (int) $child->id;
            $ids = array_merge($ids, $this->collectDescendantIds($child));
        }
        return $ids;
    }
}
