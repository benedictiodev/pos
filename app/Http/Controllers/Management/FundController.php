<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\CashAllocationFund;
use App\Models\ManagementFund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function master_fund(Request $request) {
        $data = ManagementFund::get();
        $total_fund = 0;
        foreach ($data as $item) {
            $total_fund += (int)$item->fund;
        }
        $allocation = CashAllocationFund::where('datetime', 'like', "%$request->search%")->orderBy('datetime', 'desc')->paginate(10);
        return view('management.fund.master_fund.index', [
            "allocation" => $allocation,
            "data" => $data,
            "total_fund" => $total_fund
        ]);
    }
}
