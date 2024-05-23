<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order_active() {
        return view('dashboard.order.order_active');
    }

    public function add_new_order() {
        return view('dashboard.order.new_order');
    }
}
