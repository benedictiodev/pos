<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Company::findOrFail(Auth::user()->company_id);
        return view('dashboard.company.edit', ["data" => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            "name" => 'required|unique:companies',
            "phone_number" => 'required',
            "address" => 'required'
        ]);

        $data = Company::findOrFail($id);

        if ($request->file('image')) {
            if ($request->old_image) {
                Storage::delete($request->old_image);
            }
            $validate['image'] = $request->file('image')->storeAs('images/company/product', time() . '.' . $request->image->extension());
        }

        $update = $data->update($validate);

        if ($update) {
            return redirect()->route('dashboard.company.profile')->with('success', "Successfully to update  product");
        } else {
            return redirect()->route('dashboard.company.profile')->with('failed', "Failed to update  product");
        }
    }
}
