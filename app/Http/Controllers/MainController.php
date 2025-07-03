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
                                'field_value' => 'templates/hotel-diavox/images/diavox-logo-small.jpg',
                                'field_type' => 'file',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Logo Title',
                                'field_value' => 'Hotel Diavox',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Profile Title',
                                'field_value' => 'Profile',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Profile URL',
                                'field_value' => '#',
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
                            ['field_name' => 'Title', 'field_value' => 'About', 'position' => 2],
                            ['field_name' => 'URL', 'field_value' => '/about', 'position' => 2],
                            ['field_name' => 'Title', 'field_value' => 'Contact', 'position' => 3],
                            ['field_name' => 'URL', 'field_value' => '/contact', 'position' => 3],
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
            case "Hotel Diavox":
                switch ($request->type) {
                    case "Navigation":
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Logo Image URL',
                                'field_value' => 'templates/hotel-diavox/images/diavox-logo-small.jpg',
                                'field_type' => 'file',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Logo Title',
                                'field_value' => 'Hotel Diavox',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Reservation Title',
                                'field_value' => 'Reservation',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Reservation URL',
                                'field_value' => '#',
                                'field_type' => 'text',
                            ]
                        ];

                        $blockFieldGroupsData = [
                            [
                                'group_name' => 'Menu Links',
                                'items' => [
                                    ['field_name' => 'Title', 'field_value' => 'Home', 'position' => 1],
                                    ['field_name' => 'URL', 'field_value' => '/home', 'position' => 1],
                                    ['field_name' => 'Title', 'field_value' => 'About', 'position' => 2],
                                    ['field_name' => 'URL', 'field_value' => '/about', 'position' => 2],
                                    ['field_name' => 'Title', 'field_value' => 'Our Menu', 'position' => 3],
                                    ['field_name' => 'URL', 'field_value' => '/our-enu', 'position' => 3],
                                    ['field_name' => 'Title', 'field_value' => 'Reviews', 'position' => 4],
                                    ['field_name' => 'URL', 'field_value' => '/reviews', 'position' => 4],
                                    ['field_name' => 'Title', 'field_value' => 'Contact', 'position' => 5],
                                    ['field_name' => 'URL', 'field_value' => '/contact', 'position' => 5],
                                ]
                            ]
                        ];
                        break;
                    case 'Hero':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Welcome Message',
                                'field_value' => 'Welcome to HotelDiavox.com',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Title',
                                'field_value' => 'Hotel Diavox',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Sub Title',
                                'field_value' => 'Your home, away from home.',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Left Button Text',
                                'field_value' => 'Our Story',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Left Button Link URL',
                                'field_value' => '#',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Right Button Text',
                                'field_value' => 'Check Menu',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Right Button Link URL',
                                'field_value' => '#',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Background Image URL 01',
                                'field_value' => 'templates/hotel-diavox/images/bg-hotel-diavox-01.jpg',
                                'field_type' => 'file',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Background Image URL 02',
                                'field_value' => 'templates/hotel-diavox/images/bg-hotel-diavox-02.jpg',
                                'field_type' => 'file',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Background Image URL 03',
                                'field_value' => 'templates/hotel-diavox/images/bg-hotel-diavox-03.jpg',
                                'field_type' => 'file',
                            ],

                        ];
                        break;
                    case 'About':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Video Frame URL',
                                'field_value' => 'templates/hotel-diavox/videos/reception-talking-to-guest.mp4',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Video Frame Description',
                                'field_value' => 'We Started Since 1932. The Best Hotel in the City',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Website',
                                'field_value' => 'diavox.net',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Title',
                                'field_value' => 'Hotel Diavox',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Description',
                                'field_value' => <<<HTML
                                    <p class="text-white">Your home, away from home.</p>
                                    <p class="text-white">Built in 1932 by a visionary merchant who dreamed of bringing elegance to a quiet coastal town, the Seaside Manor began as a modest guesthouse for weary travelers and local fishermen.</p>
                                HTML,
                                'field_type' => 'html',
                            ]
                        ];
                        break;
                    case 'Staff':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Top Header',
                                'field_value' => 'Creative People',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Header Title',
                                'field_value' => 'Meet Our Staff',
                                'field_type' => 'text',
                            ]
                        ];

                        $blockFieldGroupsData = [
                            [
                                'group_name' => 'Staff Cards',
                                'items' => [
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/team/boss-of-hotel.png', 'position' => 1],
                                    ['field_name' => 'Name', 'field_value' => 'Steve', 'position' => 1],
                                    ['field_name' => 'Position', 'field_value' => 'Boss', 'position' => 1],
                                    ['field_name' => 'Description', 'field_value' => 'your favorite main man. the boss!', 'position' => 1],
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/team/cute-korean-barista-girl-pouring-coffee-prepare-filter-batch-brew-pour-working-cafe.jpg', 'position' => 2],
                                    ['field_name' => 'Name', 'field_value' => 'Sandra', 'position' => 2],
                                    ['field_name' => 'Position', 'field_value' => 'Manager', 'position' => 2],
                                    ['field_name' => 'Description', 'field_value' => 'your manager who will take care of you.', 'position' => 2],
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/team/small-business-owner-drinking-coffee.jpg', 'position' => 3],
                                    ['field_name' => 'Name', 'field_value' => 'Jackson', 'position' => 3],
                                    ['field_name' => 'Position', 'field_value' => 'Reception', 'position' => 3],
                                    ['field_name' => 'Description', 'field_value' => 'your favorite person to talk a lot with.', 'position' => 3],
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/team/smiley-business-woman-working-cashier.jpg', 'position' => 4],
                                    ['field_name' => 'Name', 'field_value' => 'Michelle', 'position' => 4],
                                    ['field_name' => 'Position', 'field_value' => 'Room Service', 'position' => 4],
                                    ['field_name' => 'Description', 'field_value' => 'the most patience person inside the hotel.', 'position' => 4],
                                ]
                            ]
                        ];
                        break;
                    case 'Menu':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Background Image URL',
                                'field_value' => 'templates/hotel-diavox/images/happy-waitress-giving-coffee-customers-while-serving-them-coffee-shop.jpg',
                                'field_type' => 'file',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Left Top Header',
                                'field_value' => 'Delicious Menu',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Left Header Title',
                                'field_value' => 'Breakfast',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Right Top Header',
                                'field_value' => 'Favourite Menu',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Right Header Title',
                                'field_value' => 'Coffee',
                                'field_type' => 'text',
                            ]
                        ];

                        $blockFieldGroupsData = [
                            [
                                'group_name' => 'Left Menu Cards',
                                'items' => [
                                    ['field_name' => 'Menu Name', 'field_value' => 'Pancakes', 'position' => 1],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 1],
                                    ['field_name' => 'Price', 'field_value' => '$12.50', 'position' => 1],
                                    ['field_name' => 'Description', 'field_value' => 'Fresh whisked pancake for your honey cake.', 'position' => 1],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 1],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Toasted Waffle', 'position' => 2],
                                    ['field_name' => 'Orig Price', 'field_value' => '$16.50', 'position' => 2],
                                    ['field_name' => 'Price', 'field_value' => '$12.00', 'position' => 2],
                                    ['field_name' => 'Description', 'field_value' => 'A very toasted flavored rich waffle.', 'position' => 2],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 2],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Fried Chips', 'position' => 3],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 3],
                                    ['field_name' => 'Price', 'field_value' => '$15.00', 'position' => 3],
                                    ['field_name' => 'Description', 'field_value' => 'An aromatic deep fried chips for lips.', 'position' => 3],
                                    ['field_name' => 'Recommended', 'field_value' => 'true', 'position' => 3],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Basic Pancakes', 'position' => 4],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 4],
                                    ['field_name' => 'Price', 'field_value' => '$12.50', 'position' => 4],
                                    ['field_name' => 'Description', 'field_value' => 'Fresh whisked pancake for your honey cake.', 'position' => 4],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 4],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Banana Cakes', 'position' => 5],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 5],
                                    ['field_name' => 'Price', 'field_value' => '$18.00', 'position' => 5],
                                    ['field_name' => 'Description', 'field_value' => 'The most flavored banana for your banana lover.', 'position' => 5],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 5],
                                ]
                            ],
                            [
                                'group_name' => 'Right Menu Cards',
                                'items' => [
                                    ['field_name' => 'Menu Name', 'field_value' => 'Latte', 'position' => 1],
                                    ['field_name' => 'Orig Price', 'field_value' => '$12.50', 'position' => 1],
                                    ['field_name' => 'Price', 'field_value' => '$7.50', 'position' => 1],
                                    ['field_name' => 'Description', 'field_value' => 'Fresh brewed coffee and steamed milk. Yummy!', 'position' => 1],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 1],

                                    ['field_name' => 'Menu Name', 'field_value' => 'White Coffee', 'position' => 2],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 2],
                                    ['field_name' => 'Price', 'field_value' => '$5.50', 'position' => 2],
                                    ['field_name' => 'Description', 'field_value' => 'Brewed coffee and steamed milk.', 'position' => 2],
                                    ['field_name' => 'Recommended', 'field_value' => 'true', 'position' => 2],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Chocolate Milk', 'position' => 3],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 3],
                                    ['field_name' => 'Price', 'field_value' => '$5.50', 'position' => 3],
                                    ['field_name' => 'Description', 'field_value' => 'Rich milk and foam for rich kids', 'position' => 3],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 3],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Greentea', 'position' => 4],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 4],
                                    ['field_name' => 'Price', 'field_value' => '$7.50', 'position' => 4],
                                    ['field_name' => 'Description', 'field_value' => 'Fresh brewed coffee and steamed milk', 'position' => 4],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 4],

                                    ['field_name' => 'Menu Name', 'field_value' => 'Dark Chocolate', 'position' => 5],
                                    ['field_name' => 'Orig Price', 'field_value' => '', 'position' => 5],
                                    ['field_name' => 'Price', 'field_value' => '$7.25', 'position' => 5],
                                    ['field_name' => 'Description', 'field_value' => 'Rich Milk and Foam', 'position' => 5],
                                    ['field_name' => 'Recommended', 'field_value' => 'false', 'position' => 5],
                                ]
                            ]
                        ];
                        break;
                    case 'Reviews':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Top Header',
                                'field_value' => 'Reviews by Customers',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Header Title',
                                'field_value' => 'Testimonials',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Card Header Background',
                                'field_value' => 'templates/hotel-diavox/images/mid-section-waitress-wiping-espresso-machine-with-napkin-cafa-c.jpg',
                                'field_type' => 'file',
                            ]
                        ];

                        $blockFieldGroupsData = [
                            [
                                'group_name' => 'Review Cards',
                                'items' => [
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/reviews/young-woman-with-round-glasses-yellow-sweater.jpg', 'position' => 1],
                                    ['field_name' => 'Name', 'field_value' => 'Sandra', 'position' => 1],
                                    ['field_name' => 'Title', 'field_value' => 'Customer', 'position' => 1],
                                    ['field_name' => 'Testimonial', 'field_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'position' => 1],
                                    ['field_name' => 'Rating', 'field_value' => '4.5', 'position' => 1],
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/reviews/senior-man-white-sweater-eyeglasses.jpg', 'position' => 2],
                                    ['field_name' => 'Name', 'field_value' => 'Dondon', 'position' => 2],
                                    ['field_name' => 'Title', 'field_value' => 'Customer', 'position' => 2],
                                    ['field_name' => 'Testimonial', 'field_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'position' => 2],
                                    ['field_name' => 'Rating', 'field_value' => '3.0', 'position' => 2],
                                    ['field_name' => 'Person Picture URL', 'field_value' => 'templates/hotel-diavox/images/reviews/young-beautiful-woman-pink-warm-sweater-natural-look-smiling-portrait-isolated-long-hair.jpg', 'position' => 3],
                                    ['field_name' => 'Name', 'field_value' => 'Olivia', 'position' => 3],
                                    ['field_name' => 'Title', 'field_value' => 'Customer', 'position' => 3],
                                    ['field_name' => 'Testimonial', 'field_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'position' => 3],
                                    ['field_name' => 'Rating', 'field_value' => '5.0', 'position' => 3],
                                ]
                            ]
                        ];
                        break;
                    case 'Contacts':
                        $blockFields = [
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Top Header',
                                'field_value' => 'Say Hello',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Header Title',
                                'field_value' => 'Contact Us',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Mailto Email',
                                'field_value' => 'test@test.com',
                                'field_type' => 'text',
                            ],
                            [
                                'block_id' => $block->id,
                                'field_key' => 'Google Map iFrame Source URL',
                                'field_value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d574.0786337340072!2d121.01612404659991!3d14.546263071356298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9357b16c66f%3A0xa6d4083ca8ee93c5!2sHungry%20Homies%20-%20Chino%20Roces!5e0!3m2!1sen!2sph!4v1751424481100!5m2!1sen!2sph',
                                'field_type' => 'text',
                            ]
                        ];
                        break;
                }

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
                        'field_name' => $item['field_name'],
                        'field_value' => $item['field_value'],
                        'field_type' => 'text',
                        'position' => $item['position'] ?? 0,
                    ]);
                }
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
                        $groupItem->field_name = $item['field_name'];
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
}
