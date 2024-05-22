<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::with(['category_product' => function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        }])->paginate(5);
        return view('dashboard.master-data.product.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        $lists = CategoryProduct::all();
        return view('dashboard.master-data.product.create', ["lists" => $lists]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'image' => 'required|image|file|mimes:jpeg,png,jpg',
        ]);

        if ($request->file('image')) {
            $validate['image'] = $request->file('image')->storeAs('images/master-data/product', time() . '.' . $request->image->extension());
        }

        $validate["is_available"] = $request->is_available ? $request->is_available : 0;

        $store = Product::create([
            'name' => $validate['name'],
            'price' => $validate['price'],
            'category_id' => $validate['category_id'],
            'description' => $validate['description'],
            'image' => $validate['image'],
            'is_available' => $validate['is_available'],
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.product')->with('success', "Successfully to create  product");
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', "Failed to create  product");
        }
    }

    public function edit($id)
    {
        $data = Product::findOrFail($id);
        $lists = CategoryProduct::all();
        return view('dashboard.master-data.product.edit', ["data" => $data, "lists" => $lists]);
    }

    public function update(Request $request, $id)
    {
        $validate =  $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);

        $data = Product::findOrFail($id);

        if ($request->file('image')) {
            if ($request->old_image) {
                Storage::delete($request->old_image);
            }
            $validate['image'] = $request->file('image')->storeAs('images/master-data/product', time() . '.' . $request->image->extension());
        }

        $validate['is_available'] = $request["is_available"] ? 1 : 0;

        $update = $data->update($validate);

        if ($update) {
            return redirect()->route('dashboard.master-data.product')->with('success', "Successfully to update  product");
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', "Failed to update  product");
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        Storage::delete($product->image);
        $delete =  Product::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.master-data.product')->with('success', "Successfully to delete  product");
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', "Failed to delete  product");
        }
    }
}
