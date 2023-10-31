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
        $admin = User::create([
          'name' => "The Admin",
          'email' => 'admin@xyz.com',
          'password' => bcrypt('Admin1234$'),
        ]);

        $writer = User::create([
            'name' => "The Writer",
            'email' => 'writer@xyz.com',
            'password' => bcrypt('Writer1234$'),
        ]);

        $subscriber = User::create([
        'name' => "The Subscriber",
        'email' => 'subscriber@xyz.com',
        'password' => bcrypt('Subscriber1234$'),
        ]);

        $admin->assignRole('admin');
        $writer->assignRole('writer');
        $subscriber->assignRole('subscriber');
    }
}
