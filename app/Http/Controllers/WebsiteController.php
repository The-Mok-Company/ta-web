<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Element;
use App\Models\ElementType;
use App\Models\Language;
use App\Models\Page;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:header_setup'])->only('header');
        $this->middleware(['permission:footer_setup'])->only('footer');
        $this->middleware(['permission:view_all_website_pages'])->only('pages');
        $this->middleware(['permission:website_appearance'])->only('appearance');
        $this->middleware(['permission:select_homepage'])->only('select_homepage');
        $this->middleware(['permission:select_header'])->only('select_header');
        $this->middleware(['permission:authentication_layout_settings'])->only('authentication_layout_settings');
    }

    public function header(Request $request)
    {
        $user = Auth::user();
        $system_language = Language::where('code', app()->getLocale())->first();
        $element_type = ElementType::find(get_setting('header_element'));

        // Categories dropdown: main categories (level 0) with children, for editing in header settings
        $headerCategoriesOrder = get_setting('header_categories_order') ? json_decode(get_setting('header_categories_order'), true) : null;
        $mainCategories = Category::withoutGlobalScope('published')
            ->where('level', 0)
            ->with(['childrenCategories', 'catIcon'])
            ->orderBy('order_level', 'desc')
            ->orderBy('name')
            ->get();
        if (is_array($headerCategoriesOrder) && !empty($headerCategoriesOrder)) {
            $mainCategories = $mainCategories->sortBy(function ($cat) use ($headerCategoriesOrder) {
                $pos = array_search($cat->id, $headerCategoriesOrder);
                return $pos !== false ? $pos : 9999;
            })->values();
        }

        return view('backend.website_settings.header', compact('system_language', 'user', 'element_type', 'mainCategories', 'headerCategoriesOrder'));
    }
    public function footer(Request $request)
    {
        $lang = $request->lang;
        return view('backend.website_settings.footer', compact('lang'));
    }
    public function pages(Request $request)
    {
        $page = Page::where('type', '!=', 'home_page')->get();
        return view('backend.website_settings.pages.index', compact('page'));
    }
    public function appearance(Request $request)
    {
        return view('backend.website_settings.appearance');
    }
    public function select_homepage(Request $request)
    {
        return view('backend.website_settings.select_homepage');
    }

    public function select_header(Request $request)
    {
        $element = Element::find(1);
        $element_types = ElementType::where('element_id', $element->id)->get();
        $user = Auth::user();
        $system_language = Language::where('code', app()->getLocale())->first();
        return view('backend.website_settings.select_header', compact('element', 'element_types', 'user', 'system_language'));
    }

    public function authentication_layout_settings(Request $request)
    {
        return view('backend.website_settings.authentication_layout_settings');
    }

    public function previewHeader(Request $request)
    {
        $header_logo_id = $request->header_logo;

        if (!$header_logo_id) {
            return response()->json(['html' => ''], 400);
        }

        $img_url = uploaded_asset($header_logo_id);

        $html = '
        <a href="' . route('home') . '">
            <img src="' . $img_url . '" alt="' . env('APP_NAME') . '" class="mw-100 h-30px h-md-40px" height="40">
        </a>
    ';

        return response()->json(['html' => $html]);
    }

    public function getFileName(Request $request)
    {
        $id = $request->id;

        $upload = Upload::find($id);

        if ($upload) {
            return response()->json([
                'success' => true,
                'file_name' => $upload->file_name,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ]);
        }
    }

    public function getElementTypesByElement(Request $request)
    {
        $element_id = $request->element_id;

        $element_types = ElementType::where('element_id', $element_id)->get();

        // Attach image URL using uploaded_asset()
        $element_types->map(function ($type) {
            $upload = Upload::find($type->image_id);
            $type->image_url = $upload ? uploaded_asset($upload->id) : null;
            return $type;
        });

        return response()->json([
            'element_types' => $element_types
        ]);
    }
}
