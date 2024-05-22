<?php

namespace App\Http\Controllers;

use App\Models\RemaskCashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemarksCashFlowController extends Controller
{
    public function index() {
        $data = RemaskCashFlow::with(['category_product' => function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        }])->paginate(5);
        return view('dashboard.master-data.remarks-cash-flow.index', [
            'data' => $data
        ]);
    }

    public function create() {

    }

    public function store(Request $request) {

    }

    public function edit($id) {

    }

    public function update(Request $request, $id) {

    }

    public function destroy($id) {

    }
}
