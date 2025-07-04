<?php

namespace App\Templates;

use App\Models\Block;

class HotelDiavox implements PageTemplateInterface
{
    public function getDefaultBlockConfig(string $blockType, Block $block): array
    {
        $blockFields = [];
        $blockFieldGroupsData = [];

        switch ($blockType) {
            case 'Navigation':
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

            // Add new cases for blocks here.
            default:
                // Return empty arrays if block type is not handled
                return [
                    'fields' => [],
                    'field_groups' => []
                ];
        }

        return [
            'fields' => $blockFields,
            'field_groups' => $blockFieldGroupsData
        ];
    }
}
