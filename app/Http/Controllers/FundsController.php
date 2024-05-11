<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\HistoryFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundsController extends Controller
{
    public function index() {
        $data = Fund::where('company_id', Auth::user()->company_id)->paginate(5);
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
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.funds.edit", ["data" => $data]);
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required'
        ]);

        $data = Fund::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $update = $data->update([
                'type' => $request->type,
                'company_id' => Auth::user()->company_id
            ]);
    
            if ($update) {
                return redirect()->route('dashboard.master-data.funds')->with('success', "Successfully to update fund");
            } else {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to update fund");
            }
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function destroy($id)
    {
        $data = Fund::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) { 
            if ($data->fund > 0) {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to delete fund, funds more than 0");
            }
    
            $delete =  Fund::findOrFail($id);
            if ($delete->trashed()) {
                return redirect()->route('dashboard.master-data.funds')->with('success', "Successfully to delete fund");
            } else {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Failed to delete fund");
            }
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function funds_finance() {
        $data = Fund::where('company_id', Auth::user()->company_id)->get();
        $data_history = HistoryFund::where('company_id', Auth::user()->company_id)->orderBy('datetime', 'desc')->paginate(5);
        return view('dashboard.finance.funds.index', ['data' => $data, 'data_history' => $data_history]);
    }
}
