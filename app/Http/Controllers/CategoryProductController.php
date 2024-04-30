<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    function index()
    {
        $data = CategoryProduct::paginate(5);
        return view('dashboard.master_data.category_products.index', ['data' => $data]);
    }

    function create()
    {
        return view("dashboard.master_data.category_products.create");
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:category_products'
        ]);

        $store = CategoryProduct::create([
            'name' => $request->name,
            'company_id' => 1
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.category-products')->with('success', "Successfully to create category products");
        } else {
            return redirect()->route('dashboard.master-data.category-products')->with('failed', "Failed to create category products");
        }
    }

    function show($id)
    {
        $data = CategoryProduct::findOrFail($id);
        return view("dashboard.master_data.category_products.edit", ["data" => $data]);
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:category_products'
        ]);

        $data = CategoryProduct::findOrFail($id);

        $update = $data->update([
            'name' => $request->name,
            'company_id' => 1
        ]);

        if ($update) {
            return redirect()->route('dashboard.master-data.category-products')->with('success', "Successfully to update category products");
        } else {
            return redirect()->route('dashboard.master-data.category-products')->with('failed', "Failed to update category products");
        }
    }

    function destroy($id)
    {
        $delete =  CategoryProduct::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.master-data.category-products')->with('success', "Successfully to delete category products");
        } else {
            return redirect()->route('dashboard.master-data.category-products')->with('failed', "Failed to delete category products");
        }
    }
}
