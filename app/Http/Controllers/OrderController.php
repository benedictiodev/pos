<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function order_active() {
        return view('dashboard.order.order_active');
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
}
