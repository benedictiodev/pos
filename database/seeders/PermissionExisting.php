<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionExisting extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
