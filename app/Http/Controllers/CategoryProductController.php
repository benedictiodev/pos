<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryProductController extends Controller
{
    public function index(Request $request)
    {
        $data = CategoryProduct::query()->where('company_id', '=', Auth::user()->company_id)->where("name", "like", "%$request->search%")->paginate(10);
        return view('dashboard.master-data.category-product.index', ['data' => $data]);
    }

    public function create()
    {
        return view("dashboard.master-data.category-product.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'name' => 'required|unique:category_products'
            'name' => 'required'
        ]);

        $store = CategoryProduct::create([
            'name' => $request->name,
            'company_id' => Auth::user()->company_id
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
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.category-product.edit", ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $data = CategoryProduct::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $update = $data->update([
                'name' => $request->name,
                'company_id' => 1
            ]);

            if ($update) {
                return redirect()->route('dashboard.master-data.category-product')->with('success', "Successfully to update category product");
            } else {
                return redirect()->route('dashboard.master-data.category-product')->with('failed', "Failed to update category product");
            }
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function destroy($id)
    {
        $data =  CategoryProduct::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $delete =  CategoryProduct::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.master-data.category-product')->with('success', "Successfully to delete category product");
            } else {
                return redirect()->route('dashboard.master-data.category-product')->with('failed', "Failed to delete category product");
            }
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }
}
