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
            $total_price_item = (int) str_replace('.', '', $request['confirm_order-total_price_item']);
            $discount = (int) str_replace('.', '', $request['confirm_order-discount']);
            $total_discount = (int) str_replace('.', '', $request['confirm_order-total_discount']);
            $total_payment = (int) str_replace('.', '', $request['confirm_order-total_payment']);
            $payment = (int) str_replace('.', '', $request['confirm_order-payment']);
            $change = (int) str_replace('.', '', $request['confirm_order-change']);
            $order = json_decode($request['confirm_order-order']);

            $data_order = Order::where('company_id', Auth::user()->company_id)
                ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
                ->orderBy('sequence', 'DESC')
                ->first();
            $next_sequence = $data_order ? $data_order->sequence + 1 : 1;
            $id_order = Carbon::now()->format('Ymd') . str_pad($next_sequence, 4, '0', STR_PAD_LEFT);;

            $insert_order = Order::insertGetId([
                'company_id' => Auth::user()->company_id,
                'id_order' => $id_order,
                'customer_name' => $request['confirm_order-customer_name'],
                'cashier_name' => Auth::user()->name,
                'datetime' => Carbon::now()->toDateTimeString(),
                'total_payment' => $total_payment,
                'total_price_item' => $total_price_item,
                'discount' => $discount,
                'total_discount' => $total_discount,
                'payment' => $payment,
                'change' => $change,
                'payment_method' => $request['confirm_order-payment_method'],
                'order_type' => $request['confirm_order-order_type'],
                'status' => $request["confirm_order-pay_now"] ? 'done' : 'waiting payment',
                'remarks' => $request['confirm_order-remarks'],
                'sequence' => $next_sequence,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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

            if ($request["confirm_order-pay_now"]) {
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
            if ($request["confirm_order-pay_now"]) {
                return redirect()->route('dashboard.order.order_history');
            } else {
                return redirect()->route('dashboard.order.order_active');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_active.add_new_order')->with('failed', "Gagal menambahkan order");
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

    public function order_history_edit($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order && $order->company_id == Auth::user()->company_id) {
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

            $order_item = OrderItems::where('order_id', $id)
                ->select('order_items.*', 'products.name')
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->get();

            return view('dashboard.order.order_history_edit', [
                'list_menu' => $result_data_menu,
                'list_fund' => $data_fund,
                'order_item' => $order_item,
                'order' => $order
            ]);
        } else {
            return redirect()->route('dashboard.order.order_history')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function order_history_update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $total_price_item = (int) str_replace('.', '', $request['confirm_order-total_price_item']);
            $discount = (int) str_replace('.', '', $request['confirm_order-discount']);
            $total_discount = (int) str_replace('.', '', $request['confirm_order-total_discount']);
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
                    'total_price_item' => $total_price_item,
                    'discount' => $discount,
                    'total_discount' => $total_discount,
                    'payment' => $payment,
                    'change' => $change,
                    'payment_method' => $request['confirm_order-payment_method'],
                    'order_type' => $request['confirm_order-order_type'],
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
                OrderItems::where('id', $item)->delete();
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

            $old_data_cash = CashIn::where('company_id', Auth::user()->company_id)
                ->where('order_id', $id)->first();

            CashIn::where('id', $old_data_cash->id)->update([
                'fund' => $total_payment,
                'type' => $request['confirm_order-payment_method'],
            ]);

            $closing_cyle = ClosingCycle::where("company_id", Auth::user()->company_id)
                ->where("periode", Carbon::parse($old_data_cash->datetime)->format('Y-m'))
                ->first();

            if ($closing_cyle) {
                $fund_old = Fund::where("company_id", Auth::user()->company_id)
                    ->where("type", $old_data_cash->type)->first();
                $fund_old->update(["fund" => $fund_old->fund - $old_data_cash->fund]);

                $fund_new = Fund::where("company_id", Auth::user()->company_id)
                    ->where("type", $request["confirm_order-payment_method"])->first();
                $fund_new->update(["fund" => $fund_new->fund + $total_payment]);
            }

            $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                ->where("datetime", Carbon::parse($old_data_cash->datetime)->toDateString())->first();

            CashMonthly::where("id", $cash_monthly->id)->update([
                "kredit" => (int) $cash_monthly->kredit - $old_data_cash->fund + $total_payment,
                "amount" => (int) $cash_monthly->amount - $old_data_cash->fund + $total_payment,
                "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount - $old_data_cash->fund + $total_payment : 0,
            ]);

            DB::commit();
            return redirect()->route('dashboard.order.order_history');
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_history')->with('failed', "Gagal memperbarui riwayat order");
        }
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

    public function delete_order($id)
    {
        try {
            DB::beginTransaction();
            $data =  Order::findOrFail($id);
            if ($data && $data->company_id == Auth::user()->company_id) {
                OrderItems::where('order_id', $id)->delete();
                Order::where('id', $id)->delete();

                DB::commit();
                return redirect()->route('dashboard.order.order_active')->with('success', "Berhasil menghapus order");
            } else {
                DB::rollBack();
                return redirect()->route('dashboard.order.order_active')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.order_active')->with('failed', 'Gagal menghapus order');
        }
    }

    public function edit_order($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order && $order->company_id == Auth::user()->company_id) {
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
            return redirect()->route('dashboard.order.order_active')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update_order(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $total_price_item = (int) str_replace('.', '', $request['confirm_order-total_price_item']);
            $discount = (int) str_replace('.', '', $request['confirm_order-discount']);
            $total_discount = (int) str_replace('.', '', $request['confirm_order-total_discount']);
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
                    'total_price_item' => $total_price_item,
                    'discount' => $discount,
                    'total_discount' => $total_discount,
                    'payment' => $payment,
                    'change' => $change,
                    'payment_method' => $request['confirm_order-payment_method'],
                    'order_type' => $request['confirm_order-order_type'],
                    'status' => $request["confirm_order-pay_now"]  ? 'done' : 'waiting payment',
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
                OrderItems::where('id', $item)->delete();
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

            if ($request["confirm_order-pay_now"]) {
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
            if ($request["confirm_order-pay_now"]) {
                return redirect()->route('dashboard.order.order_history');
            } else {
                return redirect()->route('dashboard.order.order_active');
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.order.update_order')->with('failed', "Gagal memperbarui order");
        }
    }

    public function report(Request $request) {
        $month = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $month = $request->periode;
        }

        $order_month_now = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(total_payment) as total_payment"), DB::raw("COUNT(id) as total_order"))
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->first();

        $order_item = OrderItems::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->first();
        
        $order_month_chart = Order::where('company_id', Auth::user()->company_id)
            ->select(DB::raw("COUNT(total_payment) AS count_order"), DB::raw("SUM(total_payment) as total_payment"), DB::raw('Date(datetime) AS date'), DB::raw('AVG(total_payment) AS avg_payment'))
            ->where('status', 'done')
            ->where('datetime', 'like', $month . '%')
            ->groupBy(DB::raw('DATE(datetime)'))
            ->orderBy(DB::raw('DATE(datetime)'), 'ASC')
            ->get();

        $order_item_chart = OrderItems::where('company_id', Auth::user()->company_id)
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(DB::raw("SUM(quantity) as total_quantity"), DB::raw('Date(order_items.created_at) AS date'))
            ->where('status', 'done')
            ->where('order_items.created_at', 'like', $month . '%')
            ->groupBy(DB::raw('DATE(order_items.created_at)'))
            ->orderBy(DB::raw('DATE(order_items.created_at)'), 'ASC')
            ->get();

        $result_chart_order_label = array();
        $result_chart_order_value = array();
        $result_chart_order_value_avg = array();
        $result_chart_order_value_count = array();
        $result_chart_order_item_value = array();
        $index_data_chart = 0;
        $index_data_items_chart = 0;
        for ($i = 1; $i <= (int) Carbon::now()->endOfMonth()->format('d'); $i++) {
            $days = $month . '-' . ($i < 10 ? '0' . $i : $i);
            $fund = 0;
            $avg = 0;
            $count = 0;
            $count_item = 0;
            if ($index_data_chart < count($order_month_chart) && $order_month_chart[$index_data_chart]->date == $days) {
                $fund = $order_month_chart[$index_data_chart]->total_payment;
                $avg = $order_month_chart[$index_data_chart]->avg_payment;
                $count = $order_month_chart[$index_data_chart]->count_order;
                $index_data_chart += 1;
            }
            if ($index_data_items_chart < count($order_item_chart) && $order_item_chart[$index_data_items_chart]->date == $days) {
                $count_item = $order_item_chart[$index_data_items_chart]->total_quantity;
                $index_data_items_chart += 1;
            }
            array_push($result_chart_order_label, $i < 10 ? '0' . $i : $i);
            array_push($result_chart_order_value, $fund);
            array_push($result_chart_order_value_avg, $avg);
            array_push($result_chart_order_value_count, $count);
            array_push($result_chart_order_item_value, $count_item);
        }

        $product = Product::where('company_id', Auth::user()->company_id)
            ->select('products.*', 'category_products.name AS category_name')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->orderBy('category_id')
            ->orderBy('id')
            ->get();

        $result_order = array();
        foreach($product as $item) {
            $items_order = OrderItems::where('company_id', Auth::user()->company_id)
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->select(DB::raw("SUM(quantity) as total_quantity"))
                ->where('status', 'done')
                ->where('order_items.product_id', $item->id)
                ->where('order_items.created_at', 'like', $month . '%')
                ->first();
            
            $found = false;
            $product_item = (object) [
                "product_name" => $item->name,
                "sold" => $items_order->total_quantity ? (int) $items_order->total_quantity : 0,
            ];
            foreach($result_order as $key => $value) {
                if ($value->category_id == $item->category_id) {
                    $result_order[$key]->category_total_quantity += $items_order->total_quantity ? (int) $items_order->total_quantity : 0;
                    $result_order[$key]->product->push($product_item);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($result_order, (object) [
                    "category_id" => $item->category_id,
                    "category_name" => $item->category_name,
                    "category_total_quantity" => $items_order->total_quantity ? (int) $items_order->total_quantity : 0,
                    "product" => collect(),
                ]);
                $result_order[count($result_order) - 1]->product->push($product_item);
            }
        }

        foreach ($result_order AS $item) {
            $item->product = $item->product->sortByDesc('sold');
        }

        return view('dashboard.order.report', [
            'total_order' => $order_month_now,
            'total_item_order' => $order_item,
            'result_chart_order_label' => $result_chart_order_label,
            'result_chart_order_value' => $result_chart_order_value,
            'result_chart_order_value_avg' => $result_chart_order_value_avg,
            'result_chart_order_value_count' => $result_chart_order_value_count,
            'result_chart_order_item_value' => $result_chart_order_item_value,
            'result_order' => $result_order,
        ]);
    }
}
