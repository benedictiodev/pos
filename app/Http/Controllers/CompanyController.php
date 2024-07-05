<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_company()
    {
        $data = Company::findOrFail(Auth::user()->company_id);
        return view('dashboard.company.profile', ["data" => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_company(Request $request, string $id)
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

    public function index_setting()
    {
        $data = CompanySetting::query()->where("company_id", "=", Auth::user()->company_id)->first();
        return view('dashboard.company.setting', ["data" => $data]);
    }

    public function update_setting(Request $request, $id)
    {
        $validate = $request->validate([
            "distance" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ], [
            "latitude" => 'The location marker is required.',
            "longitude" => 'The location marker is required.',
        ]);
        $setting = CompanySetting::query()->where("company_id", '=', Auth::user()->company_id)->where("id", '=', $id)->first();
        if ($setting) {

            $update = $setting->update($validate);

            if ($update) {
                return redirect()->route('dashboard.company.setting')->with('success', "Successfully to update setting");
            } else {
                return redirect()->route('dashboard.company.setting')->with('failed', "Failed to update setting");
            }
        } else {
            return abort(404);
        }
    }
}
