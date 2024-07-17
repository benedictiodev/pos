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
            return redirect()->route('dashboard.master-data.category-product')->with('success', "Berhasil menambahkan data kategori produk");
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', "Gagal menambahkan data kategori produk");
        }
    }

    public function edit($id)
    {
        $data = CategoryProduct::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.category-product.edit", ["data" => $data]);
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
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
                return redirect()->route('dashboard.master-data.category-product')->with('success', "Berhasil memperbarui data kategori produk");
            } else {
                return redirect()->route('dashboard.master-data.category-product')->with('failed', "Gagal memperbarui data kategori produk");
            }
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function destroy($id)
    {
        $data =  CategoryProduct::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $delete =  CategoryProduct::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.master-data.category-product')->with('success', "Berhasil menghapus data kategori produk");
            } else {
                return redirect()->route('dashboard.master-data.category-product')->with('failed', "Gagal menghapus data kategori produk");
            }
        } else {
            return redirect()->route('dashboard.master-data.category-product')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }
}
