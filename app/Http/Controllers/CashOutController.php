<?php

namespace App\Http\Controllers;

use App\Models\CashMonthly;
use App\Models\CashOut;
use App\Models\ClosingCycle;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CashOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CashOut::where('company_id', Auth::user()->company_id)->paginate(5);
        return view('dashboard.finance.cash-out.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $funds = Fund::where('company_id', Auth::user()->company_id)->get();
        return view("dashboard.finance.cash-out.create", ["funds" => $funds]);
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
    
            $store = CashOut::create([
                'company_id' => Auth::user()->company_id,
                'fund' => $validate['fund'],
                'remark' => $validate['remark'],
                'datetime' => $validate['datetime'],
                'type' => $validate['type'],
            ]);
    
            $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];
    
            $closing_cyle = ClosingCycle::where("company_id", Auth::user()->company_id)
                ->where("periode", Carbon::parse($validate['datetime'])->format('Y-m'))
                ->first();

            if ($closing_cyle) {
                $fund = Fund::where("company_id", Auth::user()->company_id)
                    ->where("type", $request["type"])->first();
                $fund->update(["fund" => $fund->fund - $validate["fund"]]);
            }

            $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                ->where("datetime", Carbon::parse($validate['datetime'])->format('Y-m-d'))->first();

            if ($cash_monthly) {
                CashMonthly::where("id", $cash_monthly->id)->update([
                    "debit" => (int) $cash_monthly->debit + $validate["fund"],
                    "amount" => (int) $cash_monthly->amount - $validate["fund"],
                    "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount - $validate["fund"] : 0,
                ]);
            } else {
                CashMonthly::create([
                    "company_id" => Auth::user()->company_id,
                    "debit" => $validate["fund"],
                    "kredit" => 0,
                    "amount" => 0 - $validate["fund"],
                    "total_amount" => $closing_cyle ? 0 - $validate["fund"] : 0,
                    "datetime" => Carbon::parse($validate['datetime'])->format('Y-m-d')
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to create cash out");
        } catch (Throwable $error) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to create cash out");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CashOut::findOrFail($id);
        $funds = Fund::where('company_id', Auth::user()->company_id)->get();

        if ($data && $data->company_id == Auth::user()->company_id) {
            return view('dashboard.finance.cash-out.edit', [
                "data" => $data,
                "funds" => $funds,
            ]);
        } else {
            return view('dashboard.finance.cash-out')->with('failed', 'Oops! Looks like you followed a bad link. If you think this is a problem with us, please tell us.');
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
    
            $data = CashOut::findOrFail($id);
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
    
                $closing_cyle = ClosingCycle::where("company_id", Auth::user()->company_id)
                    ->where("periode", Carbon::parse($validate['datetime'])->format('Y-m'))
                    ->first();

                if ($closing_cyle) {
                    if ($type == $validate["type"]) {
                        $fund = Fund::where("company_id", Auth::user()->company_id)
                            ->where('type', $type)->first();
    
                        if ($fund) {
                            $fund->update(["fund" => $fund->fund + $amount - $validate["fund"]]);
                        }
                    } else {
                        $fundOld = Fund::where("company_id", Auth::user()->company_id)
                            ->where('type', $type)->first();
    
                        $fund = Fund::where("company_id", Auth::user()->company_id)
                            ->where('type', $validate['type'])->first();
    
                        $fundOld->update(["fund" => $fundOld->fund + $amount]);
                        $fund->update(["fund" => $fund->fund - $validate["fund"]]);
                    }
                }

                $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                    ->where("datetime", Carbon::parse($validate['datetime'])->format('Y-m-d'))->first();

                CashMonthly::where("id", $cash_monthly->id)->update([
                    "debit" => (int) $cash_monthly->debit - $amount + $validate["fund"],
                    "amount" => (int) $cash_monthly->amount + $amount - $validate["fund"],
                    "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount + $amount - $validate["fund"] : 0,
                ]);

                DB::commit();
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to update cash out");
            } else {
                DB::rollBack();
                return abort(404);
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to update cash out");
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
    
            $data = CashOut::findOrFail($id);
            if ($data && $data->company_id == Auth::user()->company_id) {
                $closing_cyle = ClosingCycle::where("company_id", Auth::user()->company_id)
                    ->where("periode", Carbon::parse($data->datetime)->format('Y-m'))
                    ->first();
                
                if ($closing_cyle) {
                    $fund = Fund::where("company_id", Auth::user()->company_id)
                        ->where('type', $data->type)->first();
                    $fund->update(['fund' => $fund->fund + $data->fund]);
                }

                $cash_monthly = CashMonthly::where("company_id", Auth::user()->company_id)
                    ->where("datetime", Carbon::parse($data->datetime)->format('Y-m-d'))->first();
                
                CashMonthly::where("id", $cash_monthly->id)->update([
                    "debit" => (int) $cash_monthly->debit - $data->fund,
                    "amount" => (int) $cash_monthly->amount + $data->fund,
                    "total_amount" => $closing_cyle ? (int) $cash_monthly->total_amount + $data->fund : 0,
                ]);

                $delete =  CashOut::destroy($id);

                DB::commit();
                return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to delete cash out");
            } else {
                DB::rollBack();
                return abort(404);
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to delete cash out");
        }
    }
}
