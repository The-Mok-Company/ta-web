<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\AboutUs;
use App\Models\OurPartners;
use Illuminate\Http\Request;

class PagesSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_website_pages']);
    }

    /**
     * Show the main pages setup index
     */
    public function index()
    {
        return view('backend.website_settings.pages_setup.index');
    }

    /**
     * Preview Homepage (Read-only)
     */
    public function previewHomepage()
    {
        // Get homepage content from settings
        $homepageSettings = [
            'slider_images' => get_setting('home_slider_images'),
            'slider_links' => get_setting('home_slider_links'),
            'banner1_images' => get_setting('home_banner1_images'),
            'banner1_links' => get_setting('home_banner1_links'),
            'banner2_images' => get_setting('home_banner2_images'),
            'banner2_links' => get_setting('home_banner2_links'),
            'featured_categories' => get_setting('featured_categories'),
        ];

        return view('backend.website_settings.pages_setup.preview_homepage', compact('homepageSettings'));
    }

    /**
     * Preview About Us (Read-only)
     */
    public function previewAboutUs()
    {
        $hero = AboutUs::where('key', 'hero')->first();
        $mission = AboutUs::where('key', 'mission')->first();
        $vision = AboutUs::where('key', 'vision')->first();

        return view('backend.website_settings.pages_setup.preview_about_us', compact('hero', 'mission', 'vision'));
    }

    /**
     * Preview Our Partners (Read-only)
     */
    public function previewOurPartners()
    {
        $hero = OurPartners::where('key', 'hero')->first();
        $trust = OurPartners::where('key', 'trust')->first();
        $brands = OurPartners::where('key', 'brands')->first();
        $count = OurPartners::where('key', 'count')->first();

        return view('backend.website_settings.pages_setup.preview_our_partners', compact('hero', 'trust', 'brands', 'count'));
    }

    /**
     * Preview Our Services (Coming Soon)
     */
    public function previewOurServices()
    {
        return view('backend.website_settings.pages_setup.preview_our_services');
    }

    /**
     * Preview Footer (Read-only)
     */
    public function previewFooter()
    {
        $footerSettings = [
            'about_us_description' => get_setting('about_us_description'),
            'play_store_link' => get_setting('play_store_link'),
            'app_store_link' => get_setting('app_store_link'),
            'frontend_copyright_text' => get_setting('frontend_copyright_text'),
            'show_social_links' => get_setting('show_social_links'),
            'facebook_link' => get_setting('facebook_link'),
            'twitter_link' => get_setting('twitter_link'),
            'instagram_link' => get_setting('instagram_link'),
            'youtube_link' => get_setting('youtube_link'),
            'linkedin_link' => get_setting('linkedin_link'),
        ];

        return view('backend.website_settings.pages_setup.preview_footer', compact('footerSettings'));
    }
}
