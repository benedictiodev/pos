<?php

namespace Database\Seeders;

use App\Models\Company;
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

        Permission::create(["name" => "keuangan-dana-lihat"]);
        Permission::create(["name" => "keuangan-dana-tambah pengalihan baru alokasi dana"]);
        Permission::create(["name" => "keuangan-arus kas harian-lihat"]);
        Permission::create(["name" => "keuangan-arus kas harian-tambah pemasukkan dana"]);
        Permission::create(["name" => "keuangan-arus kas harian-tambah pengeluaran dana"]);
        Permission::create(["name" => "keuangan-arus kas harian-perbarui dana"]);
        Permission::create(["name" => "keuangan-arus kas harian-hapus dana"]);
        Permission::create(["name" => "keuangan-arus kas bulanan-lihat"]);
        Permission::create(["name" => "keuangan-arus kas bulanan-tambah modal bulanan"]);
        Permission::create(["name" => "keuangan-arus kas bulanan-tutup buku bulanan"]);
        Permission::create(["name" => "order-order aktif-lihat"]);
        Permission::create(["name" => "order-order aktif-tambah order baru"]);
        Permission::create(["name" => "order-order aktif-perbarui order"]);
        Permission::create(["name" => "order-order aktif-hapus order"]);
        Permission::create(["name" => "order-riwayat order-lihat"]);
        Permission::create(["name" => "order-riwayat order-perbarui riwayat order"]);
        Permission::create(["name" => "order-riwayat order-lihat jumlah pendapatan order"]);
        Permission::create(["name" => "order-pelaporan-lihat"]);
        Permission::create(["name" => "toko-profil-lihat"]);
        Permission::create(["name" => "toko-profil-perbarui"]);
        Permission::create(["name" => "toko-pengaturan-lihat"]);
        Permission::create(["name" => "toko-pengaturan-perbarui"]);
        Permission::create(["name" => "master data-produk kategori-lihat"]);
        Permission::create(["name" => "master data-produk kategori-tambah"]);
        Permission::create(["name" => "master data-produk kategori-perbarui"]);
        Permission::create(["name" => "master data-produk kategori-hapus"]);
        Permission::create(["name" => "master data-produk-lihat"]);
        Permission::create(["name" => "master data-produk-tambah"]);
        Permission::create(["name" => "master data-produk-perbarui"]);
        Permission::create(["name" => "master data-produk-hapus"]);
        Permission::create(["name" => "master data-tipe dana-lihat"]);
        Permission::create(["name" => "master data-tipe dana-tambah"]);
        Permission::create(["name" => "master data-tipe dana-perbarui"]);
        Permission::create(["name" => "master data-tipe dana-hapus"]);
        Permission::create(["name" => "master data-keterangan arus kas-lihat"]);
        Permission::create(["name" => "master data-keterangan arus kas-tambah"]);
        Permission::create(["name" => "master data-keterangan arus kas-perbarui"]);
        Permission::create(["name" => "master data-keterangan arus kas-hapus"]);
        Permission::create(["name" => "pengelolaan akun-akun pengguna-lihat"]);
        Permission::create(["name" => "pengelolaan akun-akun pengguna-tambah"]);
        Permission::create(["name" => "pengelolaan akun-akun pengguna-perbarui"]);
        Permission::create(["name" => "pengelolaan akun-akun pengguna-hapus"]);
        Permission::create(["name" => "pengelolaan akun-hak akses-lihat"]);
        Permission::create(["name" => "pengelolaan akun-hak akses-tambah"]);
        Permission::create(["name" => "pengelolaan akun-hak akses-perbarui"]);
        Permission::create(["name" => "pengelolaan akun-hak akses-hapus"]);

        $owner_pro = Role::create(['name' => 'Owner-Pro', 'is_superadmin' => true])->givePermissionTo(Permission::all());
        $user = User::where('id', 1)->first();
        $user->assignRole($owner_pro);

        Company::where('id', $user->company_id)->update([
            "role_id" => $owner_pro->id,
        ]);
    }
}
