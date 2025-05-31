<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\CashAllocationFund;
use App\Models\ManagementFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FundController extends Controller
{
    public function master_fund(Request $request) {
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

    public function create_allowance_fund() {
        $funds = ManagementFund::get();
        return view('management.fund.master_fund.create', [
            'funds' => $funds,
        ]);
    }

    public function store_allowance_fund(Request $request) {
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
}
