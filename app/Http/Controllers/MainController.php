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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

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

        $request->validate([
            'siteId'     => 'required|exists:sites,id',
            'pageName'   => 'required|string|max:255',
            'templateId' => 'required|exists:templates,id',
        ]);

        $exists = Page::where('site_id', $siteId)
            ->where('name', $pageName)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => "A page named '{$pageName}' already exists for this site."
            ], 422); // 422 = Unprocessable Entity (validation error)
        }

        $pageCount = Page::where('site_id', $siteId)->count();

        if ($pageCount == 0) {
            $isLandingPage = true;
        }

        $page = Page::create([
            'site_id' => $siteId,
            'template_id' => $templateId,
            'name' => $pageName,
            'slug' => Str::slug($pageName),
            'is_landing_page' => $isLandingPage,
        ]);

        return response()->json([
            'message' => "Page '{$page->name}' created successfully.",
            'page'    => $page
        ], 201);
    }

    public function createBlock(Request $request)
    {
        $templateName = $request->templateName;

        $blockFields = [];
        $blockFieldGroup = '';

        $maxPos = Block::where('page_id', $request->pageId)->max('position') ?? 0;

        $block = Block::create([
            'page_id' => $request->pageId,
            'type' => $request->type,
            'position' => $maxPos + 1,
        ]);

        // Dynamically resolve class from template name
        /**
         * Existing Templates
         * - Hotel Diavox
         *
         * Field Types:
         * - text
         * - textarea
         * - file
         * - html
         * - select
         * - page-link (select input)
         */
        $templateClass = 'App\\Templates\\' . str_replace(' ', '', $templateName);

        if (class_exists($templateClass)) {
            /** @var PageTemplateInterface $templateInstance */
            $templateInstance = new $templateClass();

            $config = $templateInstance->getDefaultBlockConfig($request->type, $block);
            $blockFields = $config['fields'] ?? [];
            $blockFieldGroupsData = $config['field_groups'] ?? [];
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

        // Ensure the blockFieldGroup and blockFieldGroupItems are not empty before creating them
        if (!empty($blockFieldGroupsData)) {
            foreach ($blockFieldGroupsData as $groupData) {
                $blockFieldGroup = BlockFieldGroup::create([
                    'block_id' => $block->id,
                    'group_name' => $groupData['group_name'],
                ]);

                foreach ($groupData['items'] as $item) {
                    BlockFieldGroupItem::create([
                        'block_field_group_id' => $blockFieldGroup->id,
                        'field_key' => $item['field_key'],
                        'field_value' => $item['field_value'],
                        'field_type' => $item['field_type'],
                        'position' => $item['position'] ?? 0,
                    ]);
                }
            }
        }

        // Reload block with block_fields relationship
        $block->load('block_fields', 'block_field_groups.block_field_group_items');

        return response()->json($block);
    }

    public function createBlockFieldGroupItem(Request $request)
    {
        $groupId = $request->groupId;

        // Check if the block field group has any items
        if (BlockFieldGroupItem::where('block_field_group_id', $groupId)->count() == 0) {
            return response()->json(['message' => 'No items found for this block field group.'], 404);
        }

        // Get next position
        $maxPos = BlockFieldGroupItem::where('block_field_group_id', $groupId)->max('position') ?? 0;
        $newPosition = $maxPos + 1;

        // Get the template (position 1)
        $templateItems = BlockFieldGroupItem::where('block_field_group_id', $groupId)
            ->where('position', 1)
            ->get();

        if ($templateItems->isNotEmpty()) {
            foreach ($templateItems as $item) {
                BlockFieldGroupItem::create([
                    'block_field_group_id' => $item->block_field_group_id,
                    'field_key' => $item->field_key,
                    'field_value' => '', // Reset value
                    'field_type' => $item->field_type,
                    'position' => $newPosition,
                ]);
            }
        }

        // Refetch the newly created items to return to frontend
        $newItems = BlockFieldGroupItem::where('block_field_group_id', $groupId)
            ->where('position', $newPosition)
            ->get();

        return response()->json($newItems);
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
                    'id' => $field->id,
                    'field_key' => $field->field_key,
                    'field_value' => $field->field_value,
                    'field_type' => $field->field_type,
                ];
            }),
            'block_field_groups' => $block->block_field_groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'group_name' => $group->group_name,
                    'items' => $group->block_field_group_items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'field_key' => $item->field_key,
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

    public function updateBlock(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Invalid JSON payload'], 400);
        }

        $block_fields = $payload['block_fields'] ?? [];
        $block_field_groups = $payload['block_field_groups'] ?? [];

        DB::transaction(function () use ($block_fields, $block_field_groups) {
            // 🔥 Update block_fields
            foreach ($block_fields as $field) {
                $blockField = BlockField::find($field['id']);
                if ($blockField) {
                    $blockField->field_key = $field['field_key'];
                    $blockField->field_value = $field['field_value'];
                    $blockField->save();
                }
            }

            // 🔥 Update block_field_group_items
            foreach ($block_field_groups as $group) {
                $groupName = $group['group_name'];
                $items = $group['items'];

                foreach ($items as $item) {
                    $groupItem = BlockFieldGroupItem::find($item['id']);
                    if ($groupItem) {
                        //$groupItem->group_name = $groupName;
                        $groupItem->field_key = $item['field_key'];
                        $groupItem->field_value = $item['field_value'];
                        $groupItem->position = $item['position'];
                        $groupItem->save();
                    }
                }
            }
        });

        return response()->json(['message' => 'Block updated successfully.']);
    }

    public function deleteBlock(Request $request)
    {
        return Block::where('id', $request->blockId)->delete();
    }

    public function deleteBlockFieldGroupItems(Request $request)
    {
        $groupId = $request->groupId;
        $position = $request->position;

        // Delete all items in the specified group and position
        BlockFieldGroupItem::where('block_field_group_id', $groupId)
            ->where('position', $position)
            ->delete();

        $remainingItems = BlockFieldGroupItem::where('block_field_group_id', $groupId)
            ->orderBy('position')
            ->get();

        $grouped = $remainingItems->groupBy('position')->values();
        $newPosition = 1;

        foreach ($grouped as $group) {
            foreach ($group as $item) {
                $item->update(['position' => $newPosition]);
            }
            $newPosition++;
        }

        // Return all items with updated positions (flat)
        $updatedItems = BlockFieldGroupItem::where('block_field_group_id', $groupId)
            ->orderBy('position')
            ->get();

        return $updatedItems;
    }

    public function publishPage(Request $request)
    {
        $pageId = $request->pageId;

        $page = Page::find($pageId);

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $html = $request->html;

        $viewPath = resource_path('views/pages/' . $page->slug . '.blade.php');
        // Ensure the directory exists
        if (!file_exists(dirname($viewPath))) {
            mkdir(dirname($viewPath), 0755, true);
        }
        // Write the HTML content to the file
        File::put($viewPath, $html);

        return response()->json(['message' => 'Page published successfully.']);
    }

    public function showPage($slug)
    {
        // Check if the slug is valid
        $viewPath = "pages." . $slug;

        Log::info("Attempting to load view: " . $viewPath);

        // Check if the view exists
        if (!View::exists($viewPath)) {
            abort(404); // or return a fallback view
        }

        return view($viewPath);
    }
}
