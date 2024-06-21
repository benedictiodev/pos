<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $order = Order::where('company_id', Auth::user()->company_id)
            ->select()
            ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
            ->get();
        dd($order);
        return view('dashboard.dashboard.index');
    }
}
