<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CashIn::where('company_id', Auth::user()->company_id)->paginate(5);
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
        $query_data = array();
        try {
            DB::beginTransaction();

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

            DB::commit();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to create cash in");
        } catch (Throwable $error) {
            DB::rollBack();
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

        if ($data && $data->company_id == Auth::user()->company_id) {
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
        $query_data = array();
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'fund' => 'required',
                'remark' => 'required',
                'datetime' => 'required',
                'type' => 'required',
            ]);
    
            $data = CashIn::findOrFail($id);
            $type = $data->type;
            $amount = $data->fund;
    
            if ($data && $data->company_id == Auth::user()->company_id) {
                $update = $data->update([
                    'company_id' => Auth::user()->company_id,
                    'fund' => $validate['fund'],
                    'remark' => $validate['remark'],
                    'datetime' => $validate['datetime'],
                    'type' => $validate['type'],
                ]);
    
                $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];
    
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

                    $fundOld->update([
                        "fund" => $fundOld->fund - $amount
                    ]);

                    $fund->update([
                        "fund" => $fund->fund + $validate["fund"]
                    ]);
                }
                DB::commit();
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to update cash in");
            } else {
                DB::rollBack();
                return abort(404);
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to update cash in");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $query_data = array();
        try {
            DB::beginTransaction();
            if ($request->periode) {
                $query_data = ['periode' => $request->periode];
            }
    
            $data = CashIn::findOrFail($id);
            if ($data && $data->company_id == Auth::user()->company_id) {
                $fund = Fund::where(
                    "company_id",
                    Auth::user()->company_id
                )->where('type', $data->type)->first();
                $fund->update([
                    'fund' => $fund->fund - $data->fund,
                ]);
                $delete =  CashIn::destroy($id);
                
                DB::commit();
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to delete cash in");
            } else {
                DB::rollBack();
                return abort(404);
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to delete cash in");
        }
    }
}
