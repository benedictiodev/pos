<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\ClosingCycle;
use App\Models\Fund;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    public function order_active() {
        $data = Order::where("company_id", Auth::user()->company_id)
            ->whereNot('status', '!=', 'done')->paginate(10);

        return view('dashboard.order.order_active', ['data' => $data]);
    }

    public function add_new_order() {
        $data_menu = Product::select('products.*', 'category_products.name AS category_name')
        ->leftJoin('category_products', 'category_products.id', '=' , 'products.category_id')
        ->where('company_id', Auth::user()->company_id)
        ->orderBy('category_id')
        ->orderBy('products.id')
        ->get();

        $result_data_menu = array();
        foreach($data_menu as $item) {
            $find = false;
            foreach($result_data_menu as $key => $search_item) {
                if ($search_item->category_name == $item->category_name) {
                    $find = $key;
                    break;
                }
            }

            if ($find === false) {
                array_push($result_data_menu, (object) [
                    'category_name' => $item->category_name,
                    'products' => array($item),
                ]);
            } else {
                array_push($result_data_menu[$find]->products, $item);
            }
        }

        $data_fund = Fund::where('company_id', Auth::user()->company_id)->get();

        return view('dashboard.order.new_order', [
            'list_menu' => $result_data_menu,
            'list_fund' => $data_fund,
        ]);
    }

    public function post_new_order(Request $request) {
        try {
            DB::beginTransaction();
            $total_payment = (int) $request['confirm_order-total_payment'];
            $payment = (int) $request['confirm_order-payment'];
            $change = (int) $request['confirm_order-change'];
            $order = json_decode($request['confirm_order-order']);

            $insert_order = Order::insertGetId([
                'company_id' => Auth::user()->company_id,
                'id_order' => Carbon::now()->toDateString(),
                'customer_name' => $request['confirm_order-customer_name'],
                'cashier_name' => Auth::user()->name,
                'datetime' => Carbon::now()->toDateTimeString(),
                'total_payment' => $total_payment,
                'payment' => $payment,
                'change' => $change,
                'payment_method' => $request['confirm_order-payment_method'],
                'order_type' => $request['confirm_order-order_type'],
                'status' => $payment >= $total_payment ? 'done' : 'waiting payment',
                'remarks' => $request['confirm_order-remarks'],
            ]);

            foreach ($order AS $item) {
                OrderItems::create([
                    'order_id' => $insert_order,
                    'product_id' => $item->product_id,
                    'price' => $item->product_price,
                    'quantity' => $item->qty,
                    'amount' => $item->product_price * $item->qty,
                    'remarks' => $item->remarks,
                ]);
            }

            if ($payment >= $total_payment) {
                CashIn::create([
                    'company_id' => Auth::user()->company_id,
                    'fund' => $total_payment,
                    'remark' => '',
                    'datetime' => Carbon::now()->toDateTimeString(),
                    'type' => $request['confirm_order-payment_method'],
                    'order_id' => $insert_order,
                    'remarks_from_master' => '',
                ]);

                $closing_cyle = ClosingCycle::where("company_id", Auth::user()->company_id)
                    ->where("periode", Carbon::now()->format('Y-m'))
                    ->first();

                if ($closing_cyle) {
                    $fund = Fund::where("company_id", Auth::user()->company_id)
                        ->where("type", $request["confirm_order-payment_method"])->first();
                    $fund->update(["fund" => $fund->fund + $total_payment]);
                }
                
                $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                    ->where("datetime", Carbon::now()->toDateString())->first();

                if ($cash_monthly) {
                    CashMonthly::where("id", $cash_monthly->id)->update([
                        "kredit" => (int) $cash_monthly->kredit + $total_payment,
                        "amount" => (int) $cash_monthly->amount + $total_payment,
                        "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount + $total_payment : 0,
                    ]);
                } else {
                    CashMonthly::create([
                        "company_id" => Auth::user()->company_id,
                        "debit" => 0,
                        "kredit" => $total_payment,
                        "amount" => $total_payment,
                        "total_amount" => $closing_cyle ? $total_payment : 0,
                        "datetime" => Carbon::now()->toDateString()
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('dashboard.order.order_active');
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_active.add_new_order')->with('failed', "Failed to add order");
        }
    }
}
