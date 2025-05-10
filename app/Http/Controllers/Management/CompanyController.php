<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
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
            DB::transaction(function() {
                $company = Company::create([
                    "name" => "",
                    "address" => "",
                    "phone_number" => "",
                    "type_subscription" => "",
                    "subscription_fee" => "",
                    "subscription_fee" => "",
                    "expired_date" => "",
                    "grace_days_ended_at" => "",
                    "role_id" => "",
                    "settings_printer" => "",
                ]);

                return redirect()->route('management.company.index')->with('success', "Berhasil menambah data");
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Gagal menambah data");
        }
    }
}
