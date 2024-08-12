<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MigrationDataController extends Controller
{
    public function add_data_discount_for_order_old() {
        try {
            DB::beginTransaction();
            $data_order = Order::where('datetime', '<=', '2024-08-12 23:59:59')
                ->where('status', 'done')
                ->get();
    
            foreach ($data_order AS $item) {
                Order::where('id', $item->id)
                    ->update(['total_price_item' => $item->total_payment]);
            }

            DB::commit();
            dd('success');
        } catch (Throwable $error) {
            DB::rollBack();
            throw $error;
        }
    }
}
