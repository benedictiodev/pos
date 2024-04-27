<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    function index() {
        return view('dashboard.master_data.category_products.index');
    }
}
