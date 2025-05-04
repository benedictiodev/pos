<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class MigrationDataController extends Controller
{
    public function add_data_discount_for_order_old() {
        try {
            DB::beginTransaction();
            $data_order = Order::where('datetime', '<=', '2024-08-12 23:59:59')
                ->where('status', 'done')
                ->get();
    
            foreach ($data_order AS $item) {
                Order::where('id', $item->id)
                    ->update(['total_price_item' => $item->total_payment]);
            }

            DB::commit();
            dd('success');
        } catch (Throwable $error) {
            DB::rollBack();
            throw $error;
        }
    }

    public function running_seeder(Request $request) {
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

        return response()->json([
            'message' => 'Seeder berhasil dijalankan!',
        ]);
    }

    public function running_migration() {
        $owner_pro = Role::where('name', 'Owner-Pro')->first();
        $user_benedictio = User::where('company_id', 2)->first();
        if ($user_benedictio) {
            $user_benedictio->assignRole($owner_pro);
            Company::where('id', $user_benedictio->company_id)->update([
                "role_id" => $owner_pro->id,
            ]);
        }

        $role_staff = Role::create(['name' => 'Staff', 'is_superadmin' => false, 'company_id' => 1])->givePermissionTo([
            'order-order aktif-lihat', 'order-order aktif-tambah order baru', 'order-order aktif-perbarui order', 'order-order aktif-hapus order',
            'order-riwayat order-lihat'
        ]);
        $other_user = User::whereNot('company_id', 2)->whereNot('id', 1)->get();
        foreach ($other_user as $user) {
            $user->assignRole($role_staff);
        }

        return response()->json([
            'message' => 'migrate berhasil dijalankan!',
        ]);
    }

    public function optimize() {
        try {
            Artisan::call('optimize:clear');

            return response()->json([
                'message' => 'optimize berhasil dijalankan!',
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
