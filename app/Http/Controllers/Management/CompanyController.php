<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    public function index(Request $request) {
        $data = Company::where("name", "like", "%$request->search%")->paginate(10);
        return view('management.company.index', ['data' => $data]);
    }

    public function create() {
        $roles = Role::whereNull('company_id')->get();
        return view('management.company.create', ['roles' => $roles]);
    }

    public function store(Request $request) {
        try {
        DB::beginTransaction();
            $company = Company::create([
                "name" => $request->name,
                "address" => $request->address,
                "phone_number" => $request->phone_number,
                "type_subscription" => $request->type_subscription,
                "subscription_fee" => $request->subscription_fee,
                "expired_date" => Carbon::parse($request->expired_date)->format('Y-m-d') . " 23:59:59",
                "grace_days_ended_at" => Carbon::parse($request->expired_date)->addDays(7)->format('Y-m-d') . " 23:59:59",
                "role_id" => $request->role_id,
                "settings_printer" => json_encode((array) [
                    "store_name" => (array) [
                        "show" => false,
                        "value" => "",
                    ],
                    "address" => (array) [
                        "show" => false,
                        "value" => "",
                    ],
                    "wa" => (array) [
                        "show" => false,
                        "value" => "",
                    ],
                    "ig" => (array) [
                        "show" => false,
                        "value" => "",
                    ],
                    "footer" => (array) [
                        "show" => false,
                        "value" => (array) [],
                    ]
                ]),
            ]);

            $user = User::create([
                "name" => $request->account_name,
                "email" => $request->email,
                "password" => $request->password,
                "company_id" => $company->id,
                "username" => $request->username,
                "address" => $request->account_address,
                "phone_number" => $request->account_phone_number,
                "is_owner" => true,
            ]);

            $role = Role::where('id', $request->role_id)->first();
            $user->assignRole($role);

            DB::commit();
            return redirect()->route('management.company.index')->with('success', "Berhasil menambah data");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal menambah data");
        }
    }

    public function edit($id) {
        $data = Company::where('id', $id)->first();
        $roles = Role::whereNull('company_id')->get();
        return view('management.company.edit', ['roles' => $roles, 'data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $company = Company::where('id', $id)->update([
                "name" => $request->name,
                "address" => $request->address,
                "phone_number" => $request->phone_number,
                "type_subscription" => $request->type_subscription,
                "subscription_fee" => $request->subscription_fee,
                "expired_date" => Carbon::parse($request->expired_date)->format('Y-m-d') . " 23:59:59",
                "grace_days_ended_at" => Carbon::parse($request->expired_date)->addDays(7)->format('Y-m-d') . " 23:59:59",
                "role_id" => $request->role_id,
            ]);

            return redirect()->route('management.company.index')->with('success', "Berhasil memperbarui data");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal menambah data");
        }
    }
}
