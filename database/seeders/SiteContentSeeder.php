<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SiteContentSeeder extends Seeder
{
    public function run()
    {
        // Create site
        $site = DB::table('sites')->insertGetId([
            'user_id' => 1, // adjust based on existing user
            'name' => 'My Sample Site',
            'template_name' => 'default-template',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create page
        $page = DB::table('pages')->insertGetId([
            'site_id' => $site,
            'name' => 'slug-page-name',
            'is_landing_page' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // HERO block
        $heroBlockId = DB::table('blocks')->insertGetId([
            'page_id' => $page,
            'type' => 'hero',
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('block_fields')->insert([
            [
                'block_id' => $heroBlockId,
                'field_key' => 'name',
                'field_value' => 'Hero Block',
                'field_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'block_id' => $heroBlockId,
                'field_key' => 'title',
                'field_value' => 'Welcome to Our Site!',
                'field_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'block_id' => $heroBlockId,
                'field_key' => 'sub_title',
                'field_value' => 'A place to showcase your products.',
                'field_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'block_id' => $heroBlockId,
                'field_key' => 'description',
                'field_value' => 'This is a hero section with catchy text and an attractive image.',
                'field_type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // BANNER block
        $bannerBlockId = DB::table('blocks')->insertGetId([
            'page_id' => $page,
            'type' => 'banner',
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('block_fields')->insert([
            [
                'block_id' => $bannerBlockId,
                'field_key' => 'name',
                'field_value' => 'Banner Block',
                'field_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'block_id' => $bannerBlockId,
                'field_key' => 'description',
                'field_value' => 'This is a hero section with catchy text and an attractive image.',
                'field_type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // NAVIGATION block
        $navBlockId = DB::table('blocks')->insertGetId([
            'page_id' => $page,
            'type' => 'navigation',
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('block_fields')->insert([
            [
                'block_id' => $navBlockId,
                'field_key' => 'name',
                'field_value' => 'Navigation Block',
                'field_type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $groupId = DB::table('block_field_groups')->insertGetId([
            'block_id' => $navBlockId,
            'group_name' => 'centerLinks',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $centerLinks = [
            ['title' => 'Home', 'url' => '#'],
            ['title' => 'Test', 'url' => '#'],
            ['title' => 'About', 'url' => '#'],
        ];

        $groupItems = [];
        foreach ($centerLinks as $index => $link) {
            foreach ($link as $key => $value) {
                $groupItems[] = [
                    'block_field_group_id' => $groupId,
                    'field_name' => $key,
                    'field_value' => $value,
                    'field_type' => $key === 'url' ? 'url' : 'text',
                    'position' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('block_field_group_items')->insert($groupItems);
    }
}
