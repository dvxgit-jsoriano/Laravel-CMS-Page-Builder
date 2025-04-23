<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Super Administrator',
            'username' => 'system',
            'email' => 'system@example.com',
            'role' => 'System',
            'password' => bcrypt('123123123')
        ]);
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'Admin',
            'password' => bcrypt('123123123')
        ]);
        User::create([
            'name' => 'Test User',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'role' => 'Tester',
            'password' => bcrypt('123123123')
        ]);

        $this->call([
            SiteContentSeeder::class
        ]);
    }
}
