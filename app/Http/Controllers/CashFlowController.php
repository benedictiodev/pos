<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Models\CashOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    public function list_monthly(Request $request) {
        $company_id = Auth::user()->company_id;
        $periode = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $periode = $request->periode;
        }

        $cash_in = CashIn::where('datetime', 'like', $periode . '%')
            ->where('company_id', '=', $company_id)->orderBy('datetime')->get();
        $cash_out = CashIn::where('datetime', 'like', $periode . '%')
            ->where('company_id', '=', $company_id)->orderBy('datetime')->get();

        $result_data = array();
        foreach($cash_in AS $item) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->datetime)->format('d');
            $key = array_search($date, array_column($result_data, 'date'));
            if ($key) {
                $result_data[$key]->cash_in += (int)$item->fund;
            } else {
                 array_push($result_data, array([
                    'date' => $date,
                    'cash_in' => (int)$item->fund,
                    'cash_out' => 0,
                 ]));
            }
        }
        foreach($cash_out AS $item) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->datetime)->format('d');
            $key = array_search($date, array_column($result_data, 'date'));
            if ($key) {
                $result_data[$key]->cash_out += (int)$item->fund;
            } else {
                 array_push($result_data, array([
                    'date' => $date,
                    'cash_in' => 0,
                    'cash_out' => (int)$item->fund,
                 ]));
            }
        }

        usort($result_data, function($first, $second) {
            return $first->date > $second->date;
        });
    }

    public function list_daily(Request $request) {
        $company_id = Auth::user()->company_id;
        $periode = Carbon::now()->format('Y-m-d');
        if ($request->periode) {
            $periode = $request->periode;
        }

        $cash_in = CashIn::where('datetime', 'like', $periode . '%')
            ->select('*', DB::raw('"cash-in" AS type'))
            ->where('company_id', '=', $company_id)->orderBy('datetime')->get();
        $cash_out = CashOut::where('datetime', 'like', $periode . '%')
            ->select('*', DB::raw('"cash-out" AS type'))
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

    public function add_data(Request $request) {}
}
