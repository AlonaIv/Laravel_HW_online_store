<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'Edit account']);

        Permission::create(['name' => 'See admin panel']);

        Permission::create(['name' => 'Edit Products']);
        Permission::create(['name' => 'Delete Products']);
        Permission::create(['name' => 'Publish Products']);

        Permission::create(['name' => 'Edit Categories']);
        Permission::create(['name' => 'Delete Categories']);
        Permission::create(['name' => 'Publish Categories']);

        Permission::create(['name' => 'Update Orders']);

        // create roles and assign existing permissions
        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo('Edit account');

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo('See admin panel');
        $editor->givePermissionTo('Edit Products');
        $editor->givePermissionTo('Delete Products');
        $editor->givePermissionTo('Publish Products');
        $editor->givePermissionTo('Edit Categories');
        $editor->givePermissionTo('Delete Categories');
        $editor->givePermissionTo('Publish Categories');

        $admin = Role::create(['name' => 'admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
//        $user = \App\Models\User::factory()->create([
//            'name' => 'Example User',
//            'email' => 'test@example.com',
//        ]);
//        $user->assignRole($role1);
//
//        $user = \App\Models\User::factory()->create([
//            'name' => 'Example Admin User',
//            'email' => 'admin@example.com',
//        ]);
//        $user->assignRole($role2);
//
//        $user = \App\Models\User::factory()->create([
//            'name' => 'Example Super-Admin User',
//            'email' => 'superadmin@example.com',
//        ]);
//        $user->assignRole($role3);
    }
}
