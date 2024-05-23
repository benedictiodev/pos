<?php

namespace App\Http\Controllers;

use App\Models\RemaskCashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemarksCashFlowController extends Controller
{
    public function index() {
        $data = RemaskCashFlow::where('company_id', Auth::user()->company_id)
        ->paginate(5);
        return view('dashboard.master-data.remarks-cash-flow.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('dashboard.master-data.remarks-cash-flow.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $store = RemaskCashFlow::create([
            'name' => $request->name,
            'type' => $request->type,
            'company_id' => Auth::user()->company_id
        ]);

        if ($store) {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Successfully to create remarks");
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Failed to create remarks");
        }
    }

    public function edit($id) {
        $data = RemaskCashFlow::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.remarks-cash-flow.edit", ["data" => $data]);
        } else {
            return redirect()->route("dashboard.master-data.remarks-cash-flow")->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $data = RemaskCashFlow::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $update = $data->update([
                'name' => $request->name,
                'type' => $request->type,
                'company_id' => 1
            ]);
    
            if ($update) {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Successfully to update remarks");
            } else {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Failed to update remarks");
            }
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    public function destroy($id) {
        $data = RemaskCashFlow::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) { 
            $delete =  RemaskCashFlow::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Successfully to delete remarks");
            } else {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Failed to delete remarks");
            }
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }
}
