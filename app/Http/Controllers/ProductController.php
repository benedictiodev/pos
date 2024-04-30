<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    function index()
    {
        $data = Product::with('category_products')->paginate(5);
        return view('dashboard.master_data.products.index', [
            'data' => $data
        ]);
    }

    function create()
    {
        $lists = CategoryProduct::all();
        return view('dashboard.master_data.products.create', ["lists" => $lists]);
    }

    function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'image' => 'required|image|file|mimes:jpeg,png,jpg',
        ]);

        if ($request->file('image')) {
            $validate['image'] = $request->file('image')->storeAs('images/master-data/products', time() . '.' . $request->image->extension());
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
            return redirect()->route('dashboard.master-data.products')->with('success', "Successfully to create  products");
        } else {
            return redirect()->route('dashboard.master-data.products')->with('failed', "Failed to create  products");
        }
    }

    function show($id)
    {
        $data = Product::findOrFail($id);
        $lists = CategoryProduct::all();
        return view('dashboard.master_data.products.edit', ["data" => $data, "lists" => $lists]);
    }

    function update(Request $request, $id)
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
            $validate['image'] = $request->file('image')->storeAs('images/master-data/products', time() . '.' . $request->image->extension());
        }

        $validate['is_available'] = $request["is_available"] ? 1 : 0;

        $update = $data->update($validate);

        if ($update) {
            return redirect()->route('dashboard.master-data.products')->with('success', "Successfully to update  products");
        } else {
            return redirect()->route('dashboard.master-data.products')->with('failed', "Failed to update  products");
        }
    }

    function destroy($id)
    {
        $product = Product::find($id);
        Storage::delete($product->image);
        $delete =  Product::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.master-data.products')->with('success', "Successfully to delete  products");
        } else {
            return redirect()->route('dashboard.master-data.products')->with('failed', "Failed to delete  products");
        }
    }
}
