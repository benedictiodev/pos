<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundsController extends Controller
{
    public function index() {
        $data = Fund::paginate(5);
        return view('dashboard.master-data.funds.index', ['data' => $data]);
    }

    public function create()
    {
        return view("dashboard.master-data.funds.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'fund' => 'required'
        ]);

        $store = Fund::create([
            'type' => $request->type,
            'fund' => $request->fund,
            'company_id' => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.funds')->with('success', "Successfully to create fund");
        } else {
            return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to create fund");
        }
    }

    public function edit($id)
    {
        $data = Fund::findOrFail($id);
        return view("dashboard.master-data.funds.edit", ["data" => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required'
        ]);

        $data = Fund::findOrFail($id);

        $update = $data->update([
            'type' => $request->type,
            'company_id' => Auth::user()->company_id
        ]);

        if ($update) {
            return redirect()->route('dashboard.master-data.funds')->with('success', "Successfully to update fund");
        } else {
            return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to update fund");
        }
    }

    public function destroy($id)
    {
        $data = Fund::findOrFail($id);

        if ($data->fund > 0) {
            return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to delete fund, funds more than 0");
        }

        $delete =  Fund::findOrFail($id);
        if ($delete->trashed()) {
            return redirect()->route('dashboard.master-data.funds')->with('success', "Successfully to delete fund");
        } else {
            return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to delete fund");
        }
    }
}
