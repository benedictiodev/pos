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
    public function order_active()
    {
        $data = Order::where("company_id", Auth::user()->company_id)
            ->where('status', '!=', 'done')->paginate(10);

        return view('dashboard.order.order_active', ['data' => $data]);
    }

    public function add_new_order()
    {
        $data_menu = Product::select('products.*', 'category_products.name AS category_name')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('category_id')
            ->orderBy('products.id')
            ->get();

        $result_data_menu = array();
        foreach ($data_menu as $item) {
            $find = false;
            foreach ($result_data_menu as $key => $search_item) {
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

    public function post_new_order(Request $request)
    {
        try {
            DB::beginTransaction();
            $total_payment = (int) str_replace('.', '', $request['confirm_order-total_payment']);
            $payment = (int) str_replace('.', '', $request['confirm_order-payment']);
            $change = (int) str_replace('.', '', $request['confirm_order-change']);
            $order = json_decode($request['confirm_order-order']);

            $data_order = Order::where('company_id', Auth::user()->company_id)
                ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
                ->orderBy('sequence', 'DESC')
                ->first();
            $next_sequence = $data_order ? $data_order->sequence + 1 : 1;
            $id_order = Carbon::now()->format('Ymd') . str_pad($next_sequence, 4, '0', STR_PAD_LEFT); ;

            $insert_order = Order::insertGetId([
                'company_id' => Auth::user()->company_id,
                'id_order' => $id_order,
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
                'sequence' => $next_sequence,
            ]);

            foreach ($order as $item) {
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
            if ($payment >= $total_payment) {
                return redirect()->route('dashboard.order.order_history');
            } else {
                return redirect()->route('dashboard.order.order_active');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_active.add_new_order')->with('failed', "Failed to add order");
        }
    }

    public function order_history(Request $request)
    {
        $periode = Carbon::now()->format('Y-m-d');

        if ($request->periode) {
            $periode = $request->periode;
        }

        $data = Order::where('datetime', 'like', $periode . '%')->where("company_id", Auth::user()->company_id)->where("status", "done")->orderBy('datetime');

        $total = 0;
        foreach ($data->get() as $item) {
            $total += (int)$item->total_payment;
        }

        return view('dashboard.order.order_history', [
            'data' => $data->paginate(10),
            'total' => $total,
        ]);
    }

    public function order_detail($id)
    {
        $data = Order::with('items')->where("company_id", Auth::user()->company_id)->where('id', $id)->first();
        if ($data) {

            return view('dashboard.order.order_detail', [
                'data' => $data
            ]);
        } else {
            return abort(404);
        }
    }

    public function delete_order($id) {
        try {
            DB::beginTransaction();
            $data =  Order::findOrFail($id);
            if ($data && $data->company_id == Auth::user()->company_id) { 
                OrderItems::where('order_id', $id)->delete();
                Order::where('id', $id)->delete();

                DB::commit();
                return redirect()->route('dashboard.order.order_active')->with('success', "Successfully to delete order");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.order.order_active')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_active')->with('failed', 'Failed to delete order');
        }
    }

    public function edit_order($id) {
        $order = Order::where('id', $id)->first();
        if ($order && $order->company_id == Auth::user()->company_id) { 
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

            $order_item = OrderItems::where('order_id', $id)
                ->select('order_items.*', 'products.name')
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->get();

            return view('dashboard.order.update_order', [
                'list_menu' => $result_data_menu,
                'list_fund' => $data_fund,
                'order_item' => $order_item,
                'order' => $order
            ]);
        } else {
            return redirect()->route('dashboard.order.order_active')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function update_order(Request $request, $id) {
        try {
            DB::beginTransaction();
            $total_payment = (int) str_replace('.', '', $request['confirm_order-total_payment']);
            $payment = (int) str_replace('.', '', $request['confirm_order-payment']);
            $change = (int) str_replace('.', '', $request['confirm_order-change']);
            $order_add = json_decode($request['confirm_order-order_add']);
            $order_update = json_decode($request['confirm_order-order_update']);
            $order_delete = json_decode($request['confirm_order-order_delete']);

            $update_order = Order::where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->update([
                'customer_name' => $request['confirm_order-customer_name'],
                'total_payment' => $total_payment,
                'payment' => $payment,
                'change' => $change,
                'payment_method' => $request['confirm_order-payment_method'],
                'order_type' => $request['confirm_order-order_type'],
                'status' => $payment >= $total_payment ? 'done' : 'waiting payment',
                'remarks' => $request['confirm_order-remarks'],
            ]);

            foreach ($order_add as $item) {
                OrderItems::create([
                    'order_id' => $id,
                    'product_id' => $item->product_id,
                    'price' => $item->product_price,
                    'quantity' => $item->qty,
                    'amount' => $item->product_price * $item->qty,
                    'remarks' => $item->remarks,
                ]);
            }

            foreach ($order_delete as $item) {
                OrderItems::where('id', $item->id)->delete();
            }

            foreach ($order_update as $item) {
                OrderItems::where('id', $item->id)
                ->update([
                    'order_id' => $id,
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
                    'order_id' => $id,
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
            if ($payment >= $total_payment) {
                return redirect()->route('dashboard.order.order_history');
            } else {
                return redirect()->route('dashboard.order.order_active');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.update_order')->with('failed', "Failed to update order");
        }
    }
}
