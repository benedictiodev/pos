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
        $month = Carbon::now()->format('Y-m');

        $order_date_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
            ->first();

        $order_month_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"))
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->first();

        $order_item = OrderItems::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->first();

        $order_month_now_chart = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"), DB::raw('Date(datetime) AS date') )
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->groupBy(DB::raw('DATE(datetime)'))
            ->orderBy(DB::raw('DATE(datetime)'), 'ASC')
            ->get();

        $result_chart_order_label = array();
        $result_chart_order_value = array();
        $index_data_chart = 0;
        for ($i = 1; $i <= (int) Carbon::now()->endOfMonth()->format('d'); $i++) {
            $days = $month . '-' . ($i < 10 ? '0' . $i : $i);
            $fund = 0;
            if ($index_data_chart < count($order_month_now_chart) && $order_month_now_chart[$index_data_chart]->date == $days) {
                $fund = $order_month_now_chart[$index_data_chart]->total_payment;
                $index_data_chart += 1;
            }
            array_push($result_chart_order_label, $i < 10 ? '0' . $i : $i);
            array_push($result_chart_order_value, $fund);
        }

        return view('dashboard.dashboard.index', [
            'order_date_now' => $order_date_now->total_payment,
            'order_month_now' => $order_month_now->total_payment,
            'order_item' => $order_item->quantity,
            'result_chart_order_label' => $result_chart_order_label,
            'result_chart_order_value' => $result_chart_order_value,
        ]);
    }
}
