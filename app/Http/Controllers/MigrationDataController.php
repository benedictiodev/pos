<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MigrationDataController extends Controller
{
    public function add_data_discount_for_order_old() {
        $data_order = Order::all();
        
    }
}
