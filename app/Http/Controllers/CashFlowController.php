<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashMonthly;
use App\Models\CashOut;
use App\Models\ClosingCycle;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CashFlowController extends Controller
{
    public function list_monthly (Request $request) {
        $periode = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $periode = $request->periode;
        }
        $data = CashMonthly::where('company_id', Auth::user()->company_id)
            ->where('datetime', 'like', $periode . '%')->orderBy('datetime')->get();
        $fund = Fund::where('company_id', Auth::user()->company_id)->get();

        $total_cash_in = 0;
        $total_cash_out = 0;
        $total_amount = 0;

        foreach($data AS $item) {
            $total_cash_in += $item->kredit;
            $total_cash_out += $item->debit;
            $total_amount += $item->amount;
        } 

        $type_closing_cycle = 'found';
        $closing_cycle = ClosingCycle::where('periode', $periode)->first();
        if (!$closing_cycle) {
            $prev_periode = Carbon::parse($periode)->subMonth(1)->format('Y-m');
            $data_monthly_prev = CashMonthly::where('company_id', Auth::user()->company_id)
                ->where('datetime', 'like', $prev_periode . '%')->first();
            if ($data_monthly_prev) {
                $closing_cycle_prev = ClosingCycle::where('periode', $prev_periode)->first();
                if ($closing_cycle_prev) {
                    if ($closing_cycle_prev->is_done) {
                        $type_closing_cycle = 'add_equite';    
                    } else {
                        $type_closing_cycle = 'must_closing_prev';
                    }
                } else {
                    $type_closing_cycle = 'add_equite_prev';
                }
            } else {
                $type_closing_cycle = 'clear';
            }
        }

        return view('dashboard.finance.cash-flow.monthly', [
            'data' => $data, 
            'total_cash_in' => $total_cash_in, 
            'total_cash_out' => $total_cash_out,
            'total_amount' => $total_amount,
            'closing_cycle' => $closing_cycle,
            'type_closing_cycle' => $type_closing_cycle,
            'fund' => $fund,
        ]);
    }

    public function list_daily(Request $request) {
        $company_id = Auth::user()->company_id;
        $periode = Carbon::now()->format('Y-m-d');
        if ($request->periode) {
            $periode = $request->periode;
        }

        $cash_in = CashIn::where('datetime', 'like', $periode . '%')
            ->select('*', 'type AS type_fund', DB::raw('"cash-in" AS type'))
            ->where('company_id', '=', $company_id)->orderBy('datetime')->get();
        $cash_out = CashOut::where('datetime', 'like', $periode . '%')
            ->select('*', 'type AS type_fund', DB::raw('"cash-out" AS type'))
            ->where('company_id', '=', $company_id)->orderBy('datetime')->get();

        $total_cash_in = 0;
        foreach($cash_in AS $item) {
            $total_cash_in += (int)$item->fund;
        }
        $total_cash_out = 0;
        foreach($cash_out AS $item) {
            $total_cash_out += (int)$item->fund;
        }

        $result = $cash_in->push(...$cash_out);

        $sortedResult = $result->sortBy(['datetime']);
        $processedData = collect($sortedResult);

        $perPage = 5; // Replace 15 with the desired number of items per page
        $page = request()->get('page', 1); // Get the current page number from the request, default to 1
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $processedData->slice(($page - 1) * $perPage, $perPage),
            $processedData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.finance.cash-flow.daily', [
            'data' => $paginatedData, 
            'total_cash_in' => $total_cash_in, 
            'total_cash_out' => $total_cash_out
        ]);
    }

    public function add_equite(Request $request) {
        try {
            DB::beginTransaction();
            $query_data = ['periode' => $request->periode];

            ClosingCycle::create([
                'company_id' => Auth::user()->company_id,
                'periode' => $request->periode,
                'equity' => $request->equite_total,
                'target' => $request->target,
            ]);

            foreach($request->equite AS $key => $item) {
                $equite = $item ? (int) $item : 0;

                $cash_in = CashIn::where('company_id', Auth::user()->company)
                    ->where('type', $key)
                    ->where('datetime', 'like', $request->periode . '%')->get();
                
                if ($cash_in) {
                    foreach($cash_in AS $item_in) {
                        $equite += (int) $item_in->fund;
                    }
                }

                $cash_out = CashOut::where('company_id', Auth::user()->company)
                    ->where('type', $key)
                    ->where('datetime', 'like', $request->periode . '%')->get();
                
                if ($cash_out) {
                    foreach($cash_out AS $item_out) {
                        $equite -= (int) $item_out->fund;
                    }
                }

                Fund::where('company_id', Auth::user()->company_id)
                    ->where('type', $key)
                    ->update(['fund' => $equite]);
            }

            $monthly = CashMonthly::where('company_id', Auth::user()->company_id)
                ->where('datetime', 'like', $request->periode . '%')
                ->orderBy('datetime')->get();
            
            $amount = $request->equite_total ? (int) $request->equite_total : 0;
            foreach($monthly AS $item_monthly) {
                $amount += (int) $item_monthly->amount;
                CashMonthly::where('id', $item_monthly->id)
                    ->update(['total_amount' => $amount]);
            }

            DB::commit();
            return redirect()->route('dashboard.finance.cash-flow-monthly', $query_data)->with('success', "Success to add equite");
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-monthly', $query_data)->with('failed', "Failed to add equite");
        }
    }

    public function add_closing_cycle(Request $request) {
        try {
            DB::beginTransaction();
            $query_data = ['periode' => $request->periode];

            ClosingCycle::where('company_id', Auth::user()->company_id)
                ->where('periode', $request->periode)
                ->update([
                    'income' => $request->income,
                    'expenditure' => $request->expenditure,
                    'profit' => $request->profit,
                    'is_done' => 1,
                ]);

            if ($request->set_equity) {
                ClosingCycle::create([
                    'company_id' => Auth::user()->company_id,
                    'periode' => $request->next_periode,
                    'equity' => $request->next_equite_total,
                    'target' => $request->next_target,
                ]);

                foreach($request->next_equite AS $key => $item) {
                    $equite = $item ? (int) $item : 0;

                    $cash_in = CashIn::where('company_id', Auth::user()->company)
                        ->where('type', $key)
                        ->where('datetime', 'like', $request->next_periode . '%')->get();
                    
                    if ($cash_in) {
                        foreach($cash_in AS $item_in) {
                            $equite += (int) $item_in->fund;
                        }
                    }

                    $cash_out = CashOut::where('company_id', Auth::user()->company)
                        ->where('type', $key)
                        ->where('datetime', 'like', $request->next_periode . '%')->get();
                    
                    if ($cash_out) {
                        foreach($cash_out AS $item_out) {
                            $equite -= (int) $item_out->fund;
                        }
                    }

                    Fund::where('company_id', Auth::user()->company_id)
                        ->where('type', $key)
                        ->update(['fund' => $equite]);
                }

                $monthly = CashMonthly::where('company_id', Auth::user()->company_id)
                    ->where('datetime', 'like', $request->next_periode . '%')
                    ->orderBy('datetime')->get();
                
                $amount = $request->next_equite_total ? (int) $request->next_equite_total : 0;
                foreach($monthly AS $item_monthly) {
                    $amount += (int) $item_monthly->amount;
                    CashMonthly::where('id', $item_monthly->id)
                        ->update(['total_amount' => $amount]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.finance.cash-flow-monthly', $query_data)->with('success', "Success to closing cycle");
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.cash-flow-monthly', $query_data)->with('failed', "Failed to closing cycle");
        }
    }
}
