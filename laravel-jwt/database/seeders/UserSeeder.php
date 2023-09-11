<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'SiteAdmin',
            'email' => 'siteadmin@xyz.com',
            'password' => bcrypt('Abcd1234$')
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'SiteEditor',
            'email' => 'siteeditor@xyz.com',
            'password' => bcrypt('Abcd1234$')
        ]);

        $user->assignRole('editor');
    }
}
