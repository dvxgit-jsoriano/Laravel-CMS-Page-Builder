<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SiteContentSeeder extends Seeder
{
    public function run()
    {

        $template = Template::create([
            'name' => 'Hotel Diavox',
            'slug' => 'hotel-diavox',
            'desc' => 'This is the hotel diavox template'
        ]);

        // Create site
        $site = DB::table('sites')->insertGetId([
            'user_id' => 1, // adjust based on existing user
            'name' => 'My Hotel Site',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
