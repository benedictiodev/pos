<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\ClosingCycle;
use App\Models\Fund;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    public function order_active(Request $request) {
        try {
            $data = Order::where("company_id", 1)
                ->where('status', '!=', 'done')->paginate(10);

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => $data
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function add_new_order() {
        try {
            $data_menu = Product::select('products.*', 'category_products.name AS category_name')
                ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
                ->where('company_id', 1)
                ->orderBy('category_id')
                ->orderBy('products.id')
                ->get();

            $data_fund = Fund::where('company_id', 1)->get();

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => (object) [
                    "data_menu" => $data_menu,
                    "data_fund" => $data_fund,
                ]
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function post_new_order(Request $request) {
        try {
            DB::beginTransaction();
            $total_payment = (int) str_replace('.', '', $request['confirm_order-total_payment']);
            $payment = (int) str_replace('.', '', $request['confirm_order-payment']);
            $change = (int) str_replace('.', '', $request['confirm_order-change']);
            $order = json_decode($request['confirm_order-order']);

            $data_order = Order::where('company_id', 1)
                ->where('datetime', 'like', Carbon::now()->toDateString() . '%')
                ->orderBy('sequence', 'DESC')
                ->first();
            $next_sequence = $data_order ? $data_order->sequence + 1 : 1;
            $id_order = Carbon::now()->format('Ymd') . str_pad($next_sequence, 4, '0', STR_PAD_LEFT);;

            $insert_order = Order::insertGetId([
                'company_id' => 1,
                'id_order' => $id_order,
                'customer_name' => $request['confirm_order-customer_name'],
                'cashier_name' => 'Mobile',
                'datetime' => Carbon::now()->toDateTimeString(),
                'total_payment' => $total_payment,
                'payment' => $payment,
                'change' => $change,
                'payment_method' => $request['confirm_order-payment_method'],
                'order_type' => $request['confirm_order-order_type'],
                'status' => $request["confirm_order-pay_now"] ? 'done' : 'waiting payment',
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

            if ($request["confirm_order-pay_now"]) {
                CashIn::create([
                    'company_id' => 1,
                    'fund' => $total_payment,
                    'remark' => '',
                    'datetime' => Carbon::now()->toDateTimeString(),
                    'type' => $request['confirm_order-payment_method'],
                    'order_id' => $insert_order,
                    'remarks_from_master' => '',
                ]);

                $closing_cyle = ClosingCycle::where("company_id", 1)
                    ->where("periode", Carbon::now()->format('Y-m'))
                    ->first();

                if ($closing_cyle) {
                    $fund = Fund::where("company_id", 1)
                        ->where("type", $request["confirm_order-payment_method"])->first();
                    $fund->update(["fund" => $fund->fund + $total_payment]);
                }

                $cash_monthly = CashMonthly::where("company_id", 1)
                    ->where("datetime", Carbon::now()->toDateString())->first();

                if ($cash_monthly) {
                    CashMonthly::where("id", $cash_monthly->id)->update([
                        "kredit" => (int) $cash_monthly->kredit + $total_payment,
                        "amount" => (int) $cash_monthly->amount + $total_payment,
                        "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount + $total_payment : 0,
                    ]);
                } else {
                    CashMonthly::create([
                        "company_id" => 1,
                        "debit" => 0,
                        "kredit" => $total_payment,
                        "amount" => $total_payment,
                        "total_amount" => $closing_cyle ? $total_payment : 0,
                        "datetime" => Carbon::now()->toDateString()
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Order berhasil ditambahkan',
            ], 200);
        } catch (Throwable $error) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function order_history(Request $request) {
        try {
            $periode = Carbon::now()->format('Y-m-d');

            if ($request->periode) {
                $periode = $request->periode;
            }

            $data = Order::where('datetime', 'like', $periode . '%')
                ->where("company_id", 1)
                ->where("status", "done")
                ->orderBy('datetime');

            $total = 0;
            foreach ($data->get() as $item) {
                $total += (int)$item->total_payment;
            }

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => (object) [
                    "data" => $data->paginate(10),
                    "total" => $total,
                ]
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function order_history_edit($id) {
        try {
            $order = Order::where('id', $id)->first();
            $data_menu = Product::select('products.*', 'category_products.name AS category_name')
                ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
                ->where('company_id', 1)
                ->orderBy('category_id')
                ->orderBy('products.id')
                ->get();

            $data_fund = Fund::where('company_id', 1)->get();

            $order_item = OrderItems::where('order_id', $id)
                ->select('order_items.*', 'products.name')
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => (object) [
                    'list_menu' => $data_menu,
                    'list_fund' => $data_fund,
                    'order_item' => $order_item,
                    'order' => $order
                ]
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function order_history_update(Request $request, $id) {
        try {
            DB::beginTransaction();
            $total_payment = (int) str_replace('.', '', $request['confirm_order-total_payment']);
            $payment = (int) str_replace('.', '', $request['confirm_order-payment']);
            $change = (int) str_replace('.', '', $request['confirm_order-change']);
            $order_add = json_decode($request['confirm_order-order_add']);
            $order_update = json_decode($request['confirm_order-order_update']);
            $order_delete = json_decode($request['confirm_order-order_delete']);

            $update_order = Order::where('id', $id)
                ->where('company_id', 1)
                ->update([
                    'customer_name' => $request['confirm_order-customer_name'],
                    'total_payment' => $total_payment,
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

            $old_data_cash = CashIn::where('company_id', 1)
                ->where('order_id', $id)->first();

            CashIn::where('id', $old_data_cash->id)->update([
                'fund' => $total_payment,
                'type' => $request['confirm_order-payment_method'],
            ]);

            $closing_cyle = ClosingCycle::where("company_id", 1)
                ->where("periode", Carbon::parse($old_data_cash->datetime)->format('Y-m'))
                ->first();

            if ($closing_cyle) {
                $fund_old = Fund::where("company_id", 1)
                    ->where("type", $old_data_cash->type)->first();
                $fund_old->update(["fund" => $fund_old->fund - $old_data_cash->fund]);

                $fund_new = Fund::where("company_id", 1)
                    ->where("type", $request["confirm_order-payment_method"])->first();
                $fund_new->update(["fund" => $fund_new->fund + $total_payment]);
            }

            $cash_monthly = CashMonthly::where("company_id", 1)
                ->where("datetime", Carbon::parse($old_data_cash->datetime)->toDateString())->first();

            CashMonthly::where("id", $cash_monthly->id)->update([
                "kredit" => (int) $cash_monthly->kredit - $old_data_cash->fund + $total_payment,
                "amount" => (int) $cash_monthly->amount - $old_data_cash->fund + $total_payment,
                "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount - $old_data_cash->fund + $total_payment : 0,
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Order berhasil diperbaharui',
            ], 200);
        } catch (Throwable $error) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function order_detail($id) {
        try {
            $data = Order::with('items')->where("company_id", 1)->where('id', $id)->first();

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => $data,
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function delete_order($id) {
        try {
            DB::beginTransaction();
            $data =  Order::findOrFail($id);
            OrderItems::where('order_id', $id)->delete();
            Order::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Penghapusan data berhasil',
                'data' => $data,
            ], 200);
        } catch (Throwable $error) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
        }
    }

    public function edit_order($id) {
        try {
            $data_menu = Product::select('products.*', 'category_products.name AS category_name')
                ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
                ->where('company_id', 1)
                ->orderBy('category_id')
                ->orderBy('products.id')
                ->get();

            $data_fund = Fund::where('company_id', 1)->get();

            $order_item = OrderItems::where('order_id', $id)
                ->select('order_items.*', 'products.name')
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->get();

            $order = Order::where('id', $id)->first();

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => (object) [
                    "data_menu" => $data_menu,
                    "data_fund" => $data_fund,
                    "order_item" => $order_item,
                    "order" => $order,
                ]
            ], 200);
        } catch (Throwable $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500); 
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
                ->where('company_id', 1)
                ->update([
                    'customer_name' => $request['confirm_order-customer_name'],
                    'total_payment' => $total_payment,
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
                    'company_id' => 1,
                    'fund' => $total_payment,
                    'remark' => '',
                    'datetime' => Carbon::now()->toDateTimeString(),
                    'type' => $request['confirm_order-payment_method'],
                    'order_id' => $id,
                    'remarks_from_master' => '',
                ]);

                $closing_cyle = ClosingCycle::where("company_id", 1)
                    ->where("periode", Carbon::now()->format('Y-m'))
                    ->first();

                if ($closing_cyle) {
                    $fund = Fund::where("company_id", 1)
                        ->where("type", $request["confirm_order-payment_method"])->first();
                    $fund->update(["fund" => $fund->fund + $total_payment]);
                }

                $cash_monthly = CashMonthly::where("company_id", 1)
                    ->where("datetime", Carbon::now()->toDateString())->first();

                if ($cash_monthly) {
                    CashMonthly::where("id", $cash_monthly->id)->update([
                        "kredit" => (int) $cash_monthly->kredit + $total_payment,
                        "amount" => (int) $cash_monthly->amount + $total_payment,
                        "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount + $total_payment : 0,
                    ]);
                } else {
                    CashMonthly::create([
                        "company_id" => 1,
                        "debit" => 0,
                        "kredit" => $total_payment,
                        "amount" => $total_payment,
                        "total_amount" => $closing_cyle ? $total_payment : 0,
                        "datetime" => Carbon::now()->toDateString()
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Order berhasil diperbaharui',
            ], 200);
        } catch (Throwable $error) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500);  
        }
    }

    public function report() {
        return response()->json([
            'status' => 200,
            'message' => 'Fitur not avilable',
        ], 200);
    }
}
