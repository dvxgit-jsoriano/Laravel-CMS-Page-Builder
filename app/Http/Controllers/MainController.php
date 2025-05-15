<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\BlockField;
use App\Models\Page;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        // Load the current site information and get its template.
        $site = Site::where('active', true)->first();

        // Load the selected active template...
        //$template = Template::where('id', $site->template_id)->first();

        // Load the pages under this site...
        //$pages = Page::where('site_id', '1')->get();

        // Return the view page-builder
        return view('page-builder', compact('site'));
    }

    public function pages($siteId)
    {
        return Page::where('site_id', $siteId)->get();
    }

    public function pageData($id)
    {
        /*
        {
            "id": 1,
            "site_id": 1,
            "name": "slug-page-name",
            "is_landing_page": 1,
            "blocks": [
                {
                    "id": 3,
                    "page_id": 1,
                    "type": "navigation",
                    "position": 1
                },
                {
                    "id": 2,
                    "page_id": 1,
                    "type": "banner",
                    "position": 2
                },
                {
                    "id": 1,
                    "page_id": 1,
                    "type": "hero",
                    "position": 3
                }
            ]
        }
        */
        $page = Page::where('id', $id)
            ->with(['blocks' => function ($query) {
                // return ordered blocks by position column
                $query->with('block_fields')->orderBy('position', 'ASC');
            }])
            ->first();
        return response()->json($page);
    }

    public function fetchSites()
    {
        return Site::all();
    }

    public function getSiteInfo($siteId)
    {
        return Site::find($siteId);
    }

    public function fetchTemplates()
    {
        return Template::all();
    }

    public function createSite(Request $request)
    {
        $site = Site::create([
            'user_id' => 1,
            'template_id' => null,
            'name' => $request->siteName,
            'active' => false,
        ]);

        return Site::all();
    }

    public function createPage(Request $request)
    {
        $siteId = $request->siteId;
        $pageName = $request->pageName;

        Page::create([]);
    }

    public function createBlock(Request $request)
    {
        $blockFields = [];
        $maxPos = Block::where('page_id', $request->pageId)->max('position');

        $block = Block::create([
            'page_id' => $request->pageId,
            'type' => $request->blockData['type'],
            'position' => $maxPos + 1,
        ]);

        switch ($request->blockData['type']) {
            case 'hero':
                $blockFields = [
                    [
                        'block_id' => $block->id,
                        'field_key' => 'name',
                        'field_value' => 'Hero Block',
                        'field_type' => 'text',
                    ],
                    [
                        'block_id' => $block->id,
                        'field_key' => 'title',
                        'field_value' => 'Welcome to Our Site!',
                        'field_type' => 'text',
                    ],
                    [
                        'block_id' => $block->id,
                        'field_key' => 'sub_title',
                        'field_value' => 'A place to showcase your products.',
                        'field_type' => 'text',
                    ],
                    [
                        'block_id' => $block->id,
                        'field_key' => 'description',
                        'field_value' => 'This is a hero section with catchy text and an attractive image.',
                        'field_type' => 'textarea',
                    ],
                ];
                break;
            case 'navigation':
                $blockFields = [
                    [
                        'block_id' => $block->id,
                        'field_key' => 'name',
                        'field_value' => 'Hero Block',
                        'field_type' => 'text',
                    ]
                ];

            default:
        }

        foreach ($blockFields as $field) {
            BlockField::create([
                'block_id' => $block->id,
                'field_key' => $field['field_key'],
                'field_value' => $field['field_value'],
                'field_type' => $field['field_type'],
            ]);
        };
    }
}
