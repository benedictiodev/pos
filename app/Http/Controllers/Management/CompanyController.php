<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
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
        dd($request);
    }
}
