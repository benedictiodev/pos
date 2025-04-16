<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function printer_order() {
        try {
            $company_id = Auth::guard('sanctum')->user()->company_id;
            
            $settings = Company::where('id', $company_id)->first();
            $result = json_decode($settings->settings_printer);

            return response()->json([
                'status' => 200,
                'message' => 'Pengambilan data berhasil',
                'data' => $result
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500);
        }
    }
}
