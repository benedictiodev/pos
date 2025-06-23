<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\CashAllocationFund;
use App\Models\ManagementCashIn;
use App\Models\ManagementCashOut;
use App\Models\ManagementClosingCycle;
use App\Models\ManagementFund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FundController extends Controller
{
    public function master_fund(Request $request)
    {
        $data = ManagementFund::get();
        $total_fund = 0;
        foreach ($data as $item) {
            $total_fund += (int)$item->fund;
        }
        $allocation = CashAllocationFund::with(['from_type', 'to_type'])
            ->where('datetime', 'like', "%$request->search%")->orderBy('datetime', 'desc')->paginate(10);
        return view('management.fund.master_fund.index', [
            "allocation" => $allocation,
            "data" => $data,
            "total_fund" => $total_fund
        ]);
    }

    public function create_allowance_fund()
    {
        $funds = ManagementFund::get();
        return view('management.fund.master_fund.create', [
            'funds' => $funds,
        ]);
    }

    public function store_allowance_fund(Request $request)
    {
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'amount' => 'required',
                'from_type_id' => 'required',
                'to_type_id' => 'required',
                'datetime' => 'required',
            ]);

            $validate['amount'] = (int) str_replace('.', '', $validate['amount']);

            $fund_form = ManagementFund::where('id', $validate['from_type_id'])->first();

            if ($fund_form->fund - $validate['amount'] < 0) {
                DB::rollBack();
                return redirect()->route('management.fund.create_allowance_fund')->with('failed', "Nominal 'Dari tipe dana' kurang dari 'Total Dana Yang Dipindahkan'");
            }

            $fund_form->update([
                "fund" => $fund_form->fund - $validate['amount']
            ]);

            $fund_to = ManagementFund::where('id', $validate['to_type_id'])->first();

            $fund_to->update([
                "fund" => $fund_to->fund + $validate['amount']
            ]);

            CashAllocationFund::create([
                'from_type_id' => $validate['from_type_id'],
                'to_type_id' => $validate['to_type_id'],
                'amount' => $validate['amount'],
                'datetime' => $validate['datetime'],
            ]);

            DB::commit();
            return redirect()->route('management.fund.master_fund')->with('success', "Berhasil melakukan pengalihan alokasi dana");
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('management.fund.create_allowance_fund')->with('failed', "Gagal melakukan pengalihan alokasi dana");
        }
    }

    public function monthly(Request $request)
    {
        $periode = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $periode = $request->periode;
        }

        $fund = ManagementFund::get();
        $result_fund = array();
        foreach ($fund as $item) {
            array_push($result_fund, (object) array(
                'id' => $item->id,
                'name' => $item->name,
                'cash_in' => 0,
                'cash_out' => 0
            ));
        }

        $cash_in = ManagementCashIn::select('management_cash_in.*', 'management_funds.name AS type_fund', DB::raw('"cash-in" AS type'))
            ->leftJoin('management_funds', 'management_funds.id', 'management_cash_in.type_fund_id')
            ->where('datetime', 'like', $periode . '%')
            ->orderBy('datetime')->get();
        $cash_out = ManagementCashOut::select('management_cash_out.*', 'management_funds.name AS type_fund', DB::raw('"cash-out" AS type'))
            ->leftJoin('management_funds', 'management_funds.id', 'management_cash_out.type_fund_id')
            ->where('datetime', 'like', $periode . '%')
            ->orderBy('datetime')->get();

        $total_cash_in = 0;
        foreach ($cash_in as $item) {
            $total_cash_in += (int)$item->fund;

            foreach ($result_fund as $key => $value) {
                if ($value->id == $item->type_fund_id) {
                    $result_fund[$key]->cash_in += (int)$item->fund;
                    break;
                }
            }
        }

        $total_cash_out = 0;
        foreach ($cash_out as $item) {
            $total_cash_out += (int)$item->fund;

            foreach ($result_fund as $key => $value) {
                if ($value->id == $item->type_fund_id) {
                    $result_fund[$key]->cash_out += (int)$item->fund;
                    break;
                }
            }
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

        return view('management.fund.monthly.index', [
            'data' => $paginatedData,
            'total_cash_in' => $total_cash_in,
            'total_cash_out' => $total_cash_out,
            'result_fund' => $result_fund,
        ]);
    }

    public function create_cash_in()
    {
        $funds = ManagementFund::get();
        return view('management.fund.monthly.create_cash_in', ['funds' => $funds]);
    }

    public function store_cash_in(Request $request)
    {
        $query_data = array();
        try {
            DB::beginTransaction();

            $validate = $request->validate([
                'fund' => 'required',
                'remarks' => 'required',
                'datetime' => 'required',
                'type' => 'required',
            ]);

            $validate['fund'] = (int) str_replace('.', '', $validate['fund']);

            ManagementCashIn::create([
                'fund' => $validate['fund'],
                'remarks' => $request['remarks'] ?? null,
                'datetime' => $validate['datetime'],
                'type_fund_id' => $validate['type'],
            ]);

            if ($request->is_subscribed) {
                $this->handleBulkCashInSubscription($request);
            }

            $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m')];

            $closing_cyle = ManagementClosingCycle::where("periode", $query_data)
                ->first();

            if ($closing_cyle) {
                $fund = ManagementFund::where("type_fund_id", $request["type"])->first();
                $fund->update(["fund" => $fund->fund + $validate["fund"]]);
            }

            DB::commit();
            return redirect()->route('management.fund.monthly', $query_data)->with('success', "Berhasil menambahkan data pemasukkan dana");
        } catch (Throwable $error) {
            Log::info($error);
            DB::rollBack();
            return redirect()->route('management.fund.monthly', $query_data)->with('failed', "Gagal menambahkan data pemasukkan dana");
        }
    }

    private function handleBulkCashInSubscription(Request $request)
    {

        $duration = (int) $request->input('subscription_duration', 0);
        $startDateString = $request->input('datetime');

        // Duration should be greater than one
        if ($duration <= 1) {
            return;
        }

        // Check date parsing
        try {
            $currentDate = Carbon::parse($startDateString);
        } catch (\Exception $e) {
            Log::error('Invalid datetime format for subscription start: ' . $startDateString, ['error' => $e->getMessage()]);
            return;
        }

        // Prepare array
        $recordsToInsert = [];

        for ($i = 1; $i < $duration; $i++) {
            $recordDate = $currentDate->copy()->addMonths($i);

            $fund = (int) str_replace('.', '', $request->input('fund'));

            $recordsToInsert[] = [
                'fund' => $fund,
                'remarks' => $request->input('remarks') ?? null,
                'datetime' => $recordDate->format('Y-m-d\TH:i'),
                'type_fund_id' => $request->input('type'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert bulk
        if (!empty($recordsToInsert)) {
            try {
                ManagementCashIn::insert($recordsToInsert);
            } catch (\Exception $e) {
                Log::error('Failed to insert subscription records: ' . $e->getMessage(), ['data' => $recordsToInsert]);
            }
        }
    }

    public function edit_cash_in($id)
    {
        $funds = ManagementFund::get();
        $data = ManagementCashIn::where('id', $id)->first();
        return view('management.fund.monthly.edit_cash_in', ['funds' => $funds, 'data' => $data]);
    }

    public function update_cash_in(Request $request, string $id)
    {
        $query_data = array();
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'fund' => 'required',
                'remarks' => 'required',
                'datetime' => 'required',
                'type' => 'required',
            ]);

            $validate['fund'] = (int) str_replace('.', '', $validate['fund']);

            $data = ManagementCashIn::findOrFail($id);
            $type = $data->type_fund_id;
            $amount = $data->fund;

            if ($data) {
                $update = $data->update([
                    'fund' => $validate['fund'],
                    'remarks' => $request['remarks'] ?? null,
                    'datetime' => $validate['datetime'],
                    'type_fund_id' => $validate['type'],
                ]);

                $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m')];

                $closing_cyle = ManagementClosingCycle::where("periode", Carbon::parse($validate['datetime'])->format('Y-m'))
                    ->first();

                if ($closing_cyle) {
                    if ($type == $validate["type"]) {
                        $fund = ManagementFund::where('type_fund_id', $type)->first();

                        if ($fund) {
                            $fund->update(["fund" => $fund->fund - $amount + $validate["fund"]]);
                        }
                    } else {
                        $fundOld = ManagementFund::where('type_fund_id', $type)->first();

                        $fund = ManagementFund::where('type_fund_id', $validate['type'])->first();

                        $fundOld->update(["fund" => $fundOld->fund - $amount]);
                        $fund->update(["fund" => $fund->fund + $validate["fund"]]);
                    }
                }

                DB::commit();
                return redirect()->route('management.fund.monthly', $query_data)->with('success', "Berhasil memperbarui data pemasukkan dana");
            } else {
                DB::rollBack();
                return abort(404);
            }
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('management.fund.monthly.edit_cash-in', $query_data)->with('failed', "Gagal memperbarui data pemasukkan dana");
        }
    }

    public function delete_cash_in(string $id)
    {
        $cashIn = ManagementCashIn::where("id", $id)->first();
        if ($cashIn) {
            $delete = $cashIn->delete();

            $query =  ['periode' => request()->query('periode')];

            if (!$query['periode']) {
                $query['periode'] = Carbon::now()->format('Y-m');
            }

            if ($delete) {
                return redirect()->route("management.fund.monthly", $query)->with('success', "Berhasil menghapus data pemasukkan dana");
            } else {
                return redirect()->route('management.fund.monthly', $query)->with('failed', "Gagal menghapus data pemasukkan dana");
            }
        } else {
            return abort(404);
        }
    }
}
