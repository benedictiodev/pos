<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request) {
        $data = Company::where("name", "like", "%$request->search%")->paginate(10);
        return view('management.company.index', ['data' => $data]);
    }

    public function create() {
        return view('management.company.create');
    }
}
