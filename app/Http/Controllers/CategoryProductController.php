<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index()
    {
        $data = CategoryProduct::paginate(5);
        return view('dashboard.master-data.category-product.index', ['data' => $data]);
    }

    public function create()
    {
        return view("dashboard.master-data.category-product.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:category_products'
        ]);

        $store = CategoryProduct::create([
            'name' => $request->name,
            'company_id' => 1
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.category-product')->with('success', "Successfully to create category product");
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', "Failed to create category product");
        }
    }

    public function edit($id)
    {
        $data = CategoryProduct::findOrFail($id);
        return view("dashboard.master-data.category-product.edit", ["data" => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $data = CategoryProduct::findOrFail($id);

        $update = $data->update([
            'name' => $request->name,
            'company_id' => 1
        ]);

        if ($update) {
            return redirect()->route('dashboard.master-data.category-product')->with('success', "Successfully to update category product");
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', "Failed to update category product");
        }
    }

    public function destroy($id)
    {
        $delete =  CategoryProduct::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.master-data.category-product')->with('success', "Successfully to delete category product");
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', "Failed to delete category product");
        }
    }
}
