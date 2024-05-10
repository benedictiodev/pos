<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CashIn::paginate(5);
        return view('dashboard.finance.cash-in.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $funds = Fund::where('company_id', Auth::user()->company_id)->get();
        return view("dashboard.finance.cash-in.create", ['funds' => $funds]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'fund' => 'required',
            'remark' => 'required',
            'datetime' => 'required',
            'type' => 'required',
        ]);

        $store = CashIn::create([
            'company_id' => Auth::user()->company_id,
            'fund' => $validate['fund'],
            'remark' => $validate['remark'],
            'datetime' => $validate['datetime'],
            'type' => $validate['type'],
        ]);

        $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

        if ($store) {
            $fund = Fund::where(
                "company_id",
                Auth::user()->company_id
            )->where(
                "type",
                $request["type"]
            )->first();

            $fund->update([
                "fund" => $fund->fund + $validate["fund"]
            ]);

            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to create cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to create cash in");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CashIn::findOrFail($id);
        $funds = Fund::where('company_id', Auth::user()->company_id)->get();

        if ($data->company_id == Auth::user()->company_id) {
            return view('dashboard.finance.cash-in.edit', ["data" => $data, 'funds' => $funds]);
        } else {
            return view('dashboard.finance.cash-in')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'fund' => 'required',
            'remark' => 'required',
            'datetime' => 'required',
            'type' => 'required',
        ]);

        $data = CashIn::findOrFail($id);
        $type = $data->type;
        $amount = $data->fund;

        if ($data->company_id == Auth::user()->company_id) {
            $update = $data->update([
                'company_id' => Auth::user()->company_id,
                'fund' => $validate['fund'],
                'remark' => $validate['remark'],
                'datetime' => $validate['datetime'],
                'type' => $validate['type'],
            ]);

            $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

            if ($update) {
                if ($type == $validate["type"]) {
                    $fund = Fund::where(
                        "company_id",
                        Auth::user()->company_id
                    )->where('type', $type)->first();

                    if ($fund != $validate["fund"]) {
                        $fund->update([
                            "fund" => $fund->fund - $amount + $validate["fund"]
                        ]);
                    }
                } else {
                    $fundOld = Fund::where(
                        "company_id",
                        Auth::user()->company_id
                    )->where('type', $type)->first();

                    $fund = Fund::where(
                        "company_id",
                        Auth::user()->company_id
                    )->where('type', $validate['type'])->first();

                    $fundOld->where('type', $type)->update([
                        "fund" => $fundOld->fund - $amount
                    ]);

                    $fund->where('type', $validate['type'])->update([
                        "fund" => $fund->fund + $validate["fund"]
                    ]);
                }
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to update cash in");
            } else {
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to update cash in");
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $query_data = array();
        if ($request->periode) {
            $query_data = ['periode' => $request->periode];
        }

        $data = CashIn::findOrFail($id);
        if ($data->company_id == Auth::user()->company_id) {
            $delete =  CashIn::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to delete cash in");
            } else {
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to delete cash in");
            }
        } else {
            return abort(404);
        }
    }
}
