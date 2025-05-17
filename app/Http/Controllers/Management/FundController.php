<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\CashAllocationFund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function master_fund(Request $request) {
        $allocation = CashAllocationFund::where('datetime', 'like', "%$request->search%")->orderBy('datetime', 'desc')->paginate(10);
        return view('management.fund.master_fund.index', ["allocation" => $allocation]);
    }
}
