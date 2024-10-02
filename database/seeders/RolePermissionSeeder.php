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
        Role::query()->delete();
        Permission::query()->delete();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(["name" => "all-keuangan-dana-lihat"]);
        Permission::create(["name" => "all-keuangan-dana-tambah pengalihan baru alokasi dana"]);
        Permission::create(["name" => "all-keuangan-arus kas harian-lihat"]);
        Permission::create(["name" => "all-keuangan-arus kas harian-tambah pemasukkan dana"]);
        Permission::create(["name" => "all-keuangan-arus kas harian-tambah pengeluaran dana"]);
        Permission::create(["name" => "all-keuangan-arus kas harian-perbarui dana"]);
        Permission::create(["name" => "all-keuangan-arus kas harian-hapus dana"]);
        Permission::create(["name" => "all-keuangan-arus kas bulanan-lihat"]);
        Permission::create(["name" => "all-order-order aktif-lihat"]);
        Permission::create(["name" => "all-order-order aktif-tambah order baru"]);
        Permission::create(["name" => "all-order-order aktif-perbarui order"]);
        Permission::create(["name" => "all-order-order aktif-hapus order"]);
        Permission::create(["name" => "all-order-riwayat order-lihat"]);
        Permission::create(["name" => "pro-order-pelaporan-lihat"]);
        Permission::create(["name" => "all-toko-profil-lihat"]);
        Permission::create(["name" => "all-toko-profil-perbarui"]);
        Permission::create(["name" => "all-toko-pengaturan-lihat"]);
        Permission::create(["name" => "all-master data-produk kategori-lihat"]);
        Permission::create(["name" => "all-master data-produk kategori-tambah"]);
        Permission::create(["name" => "all-master data-produk kategori-perbarui"]);
        Permission::create(["name" => "all-master data-produk kategori-hapus"]);
        Permission::create(["name" => "all-master data-produk-lihat"]);
        Permission::create(["name" => "all-master data-produk-tambah"]);
        Permission::create(["name" => "all-master data-produk-perbarui"]);
        Permission::create(["name" => "all-master data-produk-hapus"]);
        Permission::create(["name" => "all-master data-tipe dana-lihat"]);
        Permission::create(["name" => "all-master data-tipe dana-tambah"]);
        Permission::create(["name" => "all-master data-tipe dana-perbarui"]);
        Permission::create(["name" => "all-master data-tipe dana-hapus"]);
        Permission::create(["name" => "all-master data-keterangan arus kas-lihat"]);
        Permission::create(["name" => "all-master data-keterangan arus kas-tambah"]);
        Permission::create(["name" => "all-master data-keterangan arus kas-perbarui"]);
        Permission::create(["name" => "all-master data-keterangan arus kas-hapus"]);

        $owner_pro = Role::create(['name' => 'Owner-Pro'])->givePermissionTo(Permission::all());
        User::where('id', 1)->first()->assignRole($owner_pro);
    }
}
