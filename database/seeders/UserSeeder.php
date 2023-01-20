<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!User::where('name', 'admin@admin.com')->exists()){
            $admin = User::factory()->withEmail('admin@admin.com')->create();
            $admin->removeRole('customer');
            $admin->assignRole('admin');
        }
        User::factory(5)->create();
    }
}
