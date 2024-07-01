<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(["name" => "view master data"]);
        Permission::create(["name" => "action master data"]);

        Permission::create(["name" => "view finance"]);
        Permission::create(["name" => "action finance"]);

        Permission::create(["name" => "view company"]);
        Permission::create(["name" => "action company"]);

        Permission::create(["name" => "view order history"]);
        Permission::create(["name" => "action order history"]);

        Permission::create(["name" => "view order active"]);
        Permission::create(["name" => "action order active"]);

        Permission::create(["name" => "view presence history"]);
        Permission::create(["name" => "action presence history"]);

        Permission::create(["name" => "view presence user"]);
        Permission::create(["name" => "action presence user"]);

        Permission::create(["name" => "view management user"]);
        Permission::create(["name" => "action management user"]);

        // Role::create(['name' => 'owner-pro']);
        // Role::create(['name' => 'owner-basic']);
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'staff']);
    }
}
