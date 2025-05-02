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
        $template = Template::where('name', 'Wicked Blocks')->first();
        return view('page-builder', compact('template'));
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
