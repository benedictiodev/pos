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
            return redirect()->route('dashboard.company.profile')->with('success', "Berhasil memperbarui data toko");
        } else {
            return redirect()->route('dashboard.company.profile')->with('failed', "Gagal memperbarui data toko");
        }
    }

    public function index_setting()
    {
        $setting_printer = Company::query()->select('settings_printer')->where("id", "=", Auth::user()->company_id)->first();
        return view('dashboard.company.setting', ["setting_printer" => json_decode($setting_printer->settings_printer)]);
    }

    public function update_setting(Request $request, $id)
    {
        $value_footer = [];
        if (isset($request['setting_printer-footer_value'])) {
            foreach($request['setting_printer-footer_value'] as $item) {
                if ($item !== null) {
                    array_push($value_footer, $item);
                }
            }
        }

        $setting_printer = json_encode((array) [
            "store_name" => (array) [
                "show" => isset($request['setting_printer-store_name_show']),
                "value" =>  $request['setting_printer-store_name_value'],
            ],
            "address" => (array) [
                "show" => isset($request['setting_printer-store_address_show']),
                "value" =>  $request['setting_printer-store_address_value'],
            ],
            "wa" => (array) [
                "show" => isset($request['setting_printer-store_whatsapp_show']),
                "value" =>  $request['setting_printer-store_whatsapp_value'],
            ],
            "ig" => (array) [
                "show" => isset($request['setting_printer-store_ig_show']),
                "value" => $request['setting_printer-store_ig_value'],
            ],
            "footer" => (array) [
                "show" => isset($request['setting_printer-footer_show']),
                "value" => $value_footer,
            ]
        ]);
        $company = Company::query()->where("id", '=', $id)->first();
        if ($company) {
            $update = $company->update([
                "settings_printer" => $setting_printer,
            ]);

            if ($update) {
                return redirect()->route('dashboard.company.setting')->with('success', "Berhasil memperbarui data pengaturan toko");
            } else {
                return redirect()->route('dashboard.company.setting')->with('failed', "Gagal memperbarui data pengaturan toko");
            }
        } else {
            return abort(404);
        }
    }
}
