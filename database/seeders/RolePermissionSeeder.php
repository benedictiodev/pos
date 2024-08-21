<?php

namespace Database\Seeders;

use App\Models\User;
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
        Permission::create(["name" => "Master Data-view"]);
        Permission::create(["name" => "Master Data-action"]);

        Permission::create(["name" => "Keuangan-view"]);
        Permission::create(["name" => "Keuangan-action"]);

        Permission::create(["name" => "Toko-view"]);
        Permission::create(["name" => "Toko-action"]);

        Permission::create(["name" => "Riwayat Order-view"]);
        Permission::create(["name" => "Riwayat Order-action"]);

        Permission::create(["name" => "Riwayat Absensi-view"]);
        Permission::create(["name" => "Riwayat Absensi-action"]);

        Permission::create(["name" => "Pengelolaan Akun-view"]);
        Permission::create(["name" => "Pengelolaan Akun-action"]);

        $owner_pro = Role::create(['name' => 'Owner-Pro'])->givePermissionTo(Permission::all());
        User::where('id', 1)->first()->assignRole($owner_pro);
    }
}
