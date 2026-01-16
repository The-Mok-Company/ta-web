<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductGroupController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check - using category permissions for now
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create', 'store');
        $this->middleware(['permission:edit_product_category'])->only('edit', 'update');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $product_groups = ProductGroup::with(['category.parentCategory', 'products'])
            ->orderBy('order_level', 'desc')
            ->orderBy('name', 'asc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $product_groups = $product_groups->where('name', 'like', '%' . $sort_search . '%');
        }

        if ($request->has('category_id')) {
            $product_groups = $product_groups->where('category_id', $request->category_id);
        }

        $product_groups = $product_groups->paginate(15);
        $sub_categories = Category::where('parent_id', '>', 0)->orderBy('name', 'asc')->get();

        return view('backend.product.product_groups.index', compact('product_groups', 'sort_search', 'sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sub_categories = Category::where('parent_id', '>', 0)
            ->with('parentCategory')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('backend.product.product_groups.create', compact('sub_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order_level' => 'nullable|integer',
            'slug' => 'nullable|string|unique:product_groups,slug',
        ]);

        // Verify that category_id is a sub-category (has parent_id > 0)
        $category = Category::findOrFail($request->category_id);
        if ($category->parent_id == 0) {
            return response()->json([
                'success' => false,
                'message' => translate('Product groups must be assigned to a sub-category, not a main category'),
            ], 422);
        }

        $product_group = new ProductGroup;
        $product_group->name = $request->name;
        $product_group->category_id = $request->category_id;
        $product_group->description = $request->description;
        $product_group->icon = $request->icon;
        $product_group->order_level = $request->order_level ?? 0;
        $product_group->active = $request->has('active') ? 1 : 1;

        if ($request->slug != null) {
            $product_group->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        } else {
            $product_group->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        }

        $product_group->save();

        return response()->json([
            'success' => true,
            'message' => translate('Product group has been created successfully'),
            'redirect' => route('product-groups.index')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product_group = ProductGroup::with(['category.parentCategory', 'products'])->findOrFail($id);
        return view('backend.product.product_groups.show', compact('product_group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product_group = ProductGroup::with('category.parentCategory')->findOrFail($id);
        $sub_categories = Category::where('parent_id', '>', 0)
            ->with('parentCategory')
            ->orderBy('name', 'asc')
            ->get();

        return view('backend.product.product_groups.edit', compact('product_group', 'sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order_level' => 'nullable|integer',
            'slug' => 'nullable|string|unique:product_groups,slug,' . $id,
        ]);

        // Verify that category_id is a sub-category (has parent_id > 0)
        $category = Category::findOrFail($request->category_id);
        if ($category->parent_id == 0) {
            return response()->json([
                'success' => false,
                'message' => translate('Product groups must be assigned to a sub-category, not a main category'),
            ], 422);
        }

        $product_group = ProductGroup::findOrFail($id);
        $product_group->name = $request->name;
        $product_group->category_id = $request->category_id;
        $product_group->description = $request->description;
        $product_group->icon = $request->icon;
        $product_group->order_level = $request->order_level ?? $product_group->order_level;
        $product_group->active = $request->has('active') ? 1 : 0;

        if ($request->slug != null) {
            $product_group->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }

        $product_group->save();

        return response()->json([
            'success' => true,
            'message' => translate('Product group has been updated successfully'),
            'redirect' => route('product-groups.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_group = ProductGroup::findOrFail($id);

        // Set product_group_id to null for all products in this group
        Product::where('product_group_id', $product_group->id)->update(['product_group_id' => null]);

        $product_group->delete();

        return 1;
    }

    /**
     * Get product groups by category (AJAX)
     */
    public function getByCategory(Request $request)
    {
        $category_id = $request->category_id;
        $product_groups = ProductGroup::where('category_id', $category_id)
            ->active()
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($product_groups);
    }
}
