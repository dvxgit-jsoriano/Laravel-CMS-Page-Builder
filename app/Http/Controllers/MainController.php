<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        // Load the selected template...
        $template = Template::where('name', 'Wicked Blocks')->first();
        // Load the pages under this site...
        $pages = Page::where('site_id', '1')->get();
        // Return the view page-builder
        return view('page-builder', compact('template', 'pages'));
    }

    public function pages($siteId)
    {
        return Page::where('site_id', $siteId)->get();
    }

    public function pageData($id)
    {
        return Page::where('id', $id)->first();
    }
}
