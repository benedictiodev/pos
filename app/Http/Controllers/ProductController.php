<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // $data = Product::with(['category_product' => function ($query) {
        //     $query->where('company_id', Auth::user()->company_id);
        // }])->paginate(5);
        $data = Product::query()->select('products.*')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->where('company_id', '=', Auth::user()->company_id)
            ->where("products.name", "like", "%$request->search%")
            ->paginate(5);

        return view('dashboard.master-data.product.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        $lists = CategoryProduct::where('company_id', Auth::user()->company_id)->get();
        return view('dashboard.master-data.product.create', ["lists" => $lists]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            // 'description' => 'required',
            // 'image' => 'required|image|file|mimes:jpeg,png,jpg',
        ]);

        if ($request->file('image')) {
            $request['image'] = $request->file('image')->storeAs('images/master-data/product', time() . '.' . $request->image->extension());
        }

        $validate["is_available"] = $request->is_available ? $request->is_available : 0;

        $store = Product::create([
            'name' => $validate['name'],
            'price' => $validate['price'],
            'category_id' => $validate['category_id'],
            'description' => $request['description'],
            'image' => $request['image'],
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
        // $data = Product::findOrFail($id);
        $data = Product::select('products.*', 'company_id')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->where('company_id', Auth::user()->company_id)
            ->where('products.id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            $lists = CategoryProduct::where('company_id', Auth::user()->company_id)->get();
            return view('dashboard.master-data.product.edit', ["data" => $data, "lists" => $lists]);
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function update(Request $request, $id)
    {
        $validate =  $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            // 'description' => 'required',
        ]);

        // $data = Product::findOrFail($id);
        $data = Product::select('products.*', 'company_id')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->where('company_id', Auth::user()->company_id)
            ->where('products.id', $id)
            ->first();

        if ($data && $data->company_id == Auth::user()->company_id) {
            if ($request->file('image')) {
                if ($request->old_image) {
                    Storage::delete($request->old_image);
                }
                $request['image'] = $request->file('image')->storeAs('images/master-data/product', time() . '.' . $request->image->extension());
            }

            $validate['is_available'] = $request["is_available"] ? 1 : 0;

            $update = $data->update([
                'name' => $validate['name'],
                'price' => $validate['price'],
                'category_id' => $validate['category_id'],
                'description' => $request['description'],
                'image' => $request['image'],
                'is_available' => $validate['is_available'],
            ]);

            if ($update) {
                return redirect()->route('dashboard.master-data.product')->with('success', "Successfully to update  product");
            } else {
                return redirect()->route('dashboard.master-data.product')->with('failed', "Failed to update  product");
            }
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function destroy($id)
    {
        // $product = Product::find($id);
        $product = Product::select('products.*', 'company_id')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_id')
            ->where('company_id', Auth::user()->company_id)
            ->where('products.id', $id)
            ->first();

        if ($product && $product->company_id == Auth::user()->company_id) {
            Storage::delete($product->image);
            $delete =  Product::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.master-data.product')->with('success', "Successfully to delete  product");
            } else {
                return redirect()->route('dashboard.master-data.product')->with('failed', "Failed to delete  product");
            }
        } else {
            return redirect()->route('dashboard.master-data.product')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }
}
