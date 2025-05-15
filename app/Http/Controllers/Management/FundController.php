<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function master_fund() {
        return view('management.fund.master_fund.index');
    }
}
