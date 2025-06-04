<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\BlockField;
use App\Models\BlockFieldGroup;
use App\Models\BlockFieldGroupItem;
use App\Models\Page;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        // Load the current site information and get its template.
        $sites = Site::all();
        //$site = Site::where('active', true)->first();

        // Load the selected active template...
        //$template = Template::where('id', $site->template_id)->first();

        // Load the pages under this site...
        //$pages = Page::where('site_id', '1')->get();

        $templates = Template::all();

        // Return the view page-setup
        return view('page-setup', compact('sites', 'templates'));
    }

    public function process_builder(Request $request)
    {
        $siteName = $request->new_site;
        $siteId = $request->existing_site;
        $templateId = $request->template_id;

        // Check if the site name input is not empty
        if (!empty($siteName)) {
            // Create the site
            $site = Site::create([
                'user_id' => 1,
                'name' => $siteName,
                'active' => false,
            ]);
        } else {
            // Use the existing site
            $site = Site::findOrFail($siteId);
        }

        $template = Template::findOrFail($templateId);

        // Store in page builder sessions to handle page GET and POST
        session([
            'page_builder.site_id' => $site->id,
            'page_builder.template_id' => $template->id,
        ]);

        // Redirect to page-builder page
        return redirect()->route('page-builder');
    }

    public function index_builder()
    {
        // Get the current session if existing and valid
        $siteId = session('page_builder.site_id');
        $templateId = session('page_builder.template_id');

        // Abort process if there is no siteId or templateId
        if (!$siteId || !$templateId) {
            abort(404, 'Site or template not found in session.');
        }

        $site = Site::findOrFail($siteId);
        $template = Template::findOrFail($templateId);

        return view('page-builder', compact('site', 'template'));
    }

    public function pages($siteId)
    {
        return Page::where('site_id', $siteId)->get();
    }

    public function pageData($id)
    {
        $page = Page::where('id', $id)
            ->with(['blocks' => function ($query) {
                // return ordered blocks by position column
                $query->with(
                    'block_fields',
                    'block_field_groups.block_field_group_items'
                )->orderBy('position', 'ASC');
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
            'name' => $request->siteName,
            'active' => false,
        ]);

        return Site::all();
    }

    public function createPage(Request $request)
    {
        $siteId = $request->siteId;
        $pageName = $request->pageName;
        $templateId = $request->templateId;
        $isLandingPage = false;

        $pageCount = Page::where('site_id', $siteId)->count();

        if ($pageCount == 0) $isLandingPage = true;

        Page::create([
            'site_id' => $siteId,
            'template_id' => $templateId,
            'name' => $pageName,
            'slug' => Str::slug($pageName),
            'is_landing_page' => $isLandingPage
        ]);
    }

    public function createBlock(Request $request)
    {
        $templateName = $request->templateName;

        $blockFields = [];
        $blockFieldGroup = '';
        $blockFieldGroupItems = [];

        $maxPos = Block::where('page_id', $request->pageId)->max('position') ?? 0;

        $block = Block::create([
            'page_id' => $request->pageId,
            'type' => $request->type,
            'position' => $maxPos + 1,
        ]);

        switch ($templateName) {
            case "Default Template":
                switch ($request->type) {
                    case 'navigation':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Logo Image URL',
                                'field_value' => 'https://cdn-icons-png.flaticon.com/128/2202/2202112.png',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Logo Title',
                                'field_value' => 'Navi',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Profile Title',
                                'field_value' => 'Navi',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Profile URL',
                                'field_value' => 'Navi',
                                'field_type' => 'text',
                            ]
                        ];

                        // create a group called Center Links
                        $blockFieldGroup = BlockFieldGroup::create([
                            'block_id' => $block->id,
                            'group_name' => 'Center Links',
                        ]);

                        $blockFieldGroupItems = [
                            ['field_name' => 'Title', 'field_value' => 'Home', 'position' => 1],
                            ['field_name' => 'URL', 'field_value' => '/home', 'position' => 1],
                            ['field_name' => 'Title', 'field_value' => 'Profile', 'position' => 2],
                            ['field_name' => 'URL', 'field_value' => '/profile', 'position' => 2],
                            ['field_name' => 'Title', 'field_value' => 'Contacts', 'position' => 3],
                            ['field_name' => 'URL', 'field_value' => '/contacts', 'position' => 3],
                        ];
                        break;

                    case 'hero':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Title',
                                'field_value' => 'Welcome to Our Site!',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Description',
                                'field_value' => 'This is a hero section with catchy text and an attractive image.',
                                'field_type' => 'textarea',
                            ],
                        ];
                        break;

                    default:
                }
                break;
            case "Wicked Blocks":
                switch ($request->type) {
                    case 'navigation header':

                        break;
                    default:
                }
                break;
            default;
        }

        if (!empty($blockFields)) {
            foreach ($blockFields as $field) {
                BlockField::create([
                    'block_id' => $block->id,
                    'field_key' => $field['field_key'],
                    'field_value' => $field['field_value'],
                    'field_type' => $field['field_type'],
                ]);
            };
        }

        if ($blockFieldGroup && !empty($blockFieldGroupItems)) {
            foreach ($blockFieldGroupItems as $item) {
                BlockFieldGroupItem::create([
                    'block_field_group_id' => $blockFieldGroup->id,
                    'field_name' => $item['field_name'],
                    'field_value' => $item['field_value'],
                    'field_type' => 'text',
                    'position' => $item['position'] ?? 0,
                ]);
            }
        }

        // Reload block with block_fields relationship
        $block->load('block_fields', 'block_field_groups.block_field_group_items');

        return response()->json($block);
    }

    public function getPages($siteId, Request $request)
    {
        $templateId = $request->templateId;
        return Page::where('site_id', $siteId)->where('template_id', $templateId)->get();
    }

    public function setTemplateToSite(Request $request)
    {
        $siteId = $request->siteId;
        $templateId = $request->templateId;

        // Clear all page contents first.
        Page::where('site_id', $siteId)->delete();
        /* $page = Page::where('site_id', $siteId)->first();

        $blocks = Block::where('page_id', $page->id)->get();

        $blockFields = BlockField::where('block_id', $blocks->id)->get();

        $blockFieldGroups = BlockFieldGroup::where('block_field_id', $blockFields->id)->get();

        $blockFieldGroupItems = BlockFieldGroupItem::where('block_field_group_id', $blockFieldGroups->id)->get();
        */
        // Set the selected template
        Site::where('site_id', $siteId)
            ->update([
                'template_id' => $templateId
            ]);
    }

    public function getBlockSet(Request $request)
    {
        $blockId = $request->blockId;

        $block = Block::with([
            'block_fields',
            'block_field_groups.block_field_group_items'
        ])->findOrFail($blockId);

        $data = [
            'page_id' => $block->page_id,
            'type' => $block->type,
            'position' => $block->position,
            'block_fields' => $block->block_fields->map(function ($field) {
                return [
                    'field_key' => $field->field_key,
                    'field_value' => $field->field_value,
                    'field_type' => $field->field_type,
                ];
            }),
            'block_field_groups' => $block->block_field_groups->map(function ($group) {
                return [
                    'group_name' => $group->group_name,
                    'items' => $group->block_field_group_items->map(function ($item) {
                        return [
                            'field_name' => $item->field_name,
                            'field_value' => $item->field_value,
                            'field_type' => $item->field_type,
                            'position' => $item->position,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($data);
    }

    public function updateBlockPositions(Request $request)
    {
        foreach ($request->positions as $positionData) {
            Block::where('id', $positionData['id'])->update(['position' => $positionData['position']]);
        }

        return response()->json(['status' => 'success']);
    }
}
