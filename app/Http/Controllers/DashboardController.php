<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $order_date_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
            ->first();

        $order_month_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('datetime', 'like', Carbon::now()->format('Y-m') . '%')
            ->first();

        $order_item = OrderItems::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('status', 'done')
            ->where('datetime', 'like', Carbon::now()->format('Y-m') . '%')
            ->first();

        return view('dashboard.dashboard.index', [
            'order_date_now' => $order_date_now->total_payment,
            'order_month_now' => $order_month_now->total_payment,
            'order_item' => $order_item->quantity,
        ]);
    }
}
