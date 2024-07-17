<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\HistoryFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class FundsController extends Controller
{
    public function index(Request $request)
    {
        $data = Fund::query()->where('company_id', '=', Auth::user()->company_id)->where("type", "like", "%$request->search%")->paginate(10);
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
            return redirect()->route('dashboard.master-data.funds')->with('success', "Berhasil menambahkan data tipe dana");
        } else {
            return redirect()->route('dashboard.master-data.funds')->with('failed', "Gagal menambahkan data tipe dana");
        }
    }

    public function edit($id)
    {
        $data = Fund::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.funds.edit", ["data" => $data]);
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
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
                return redirect()->route('dashboard.master-data.funds')->with('success', "Berhasil memperbarui data tipe dana");
            } else {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Gagal memperbarui data tipe dana");
            }
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function destroy($id)
    {
        $data = Fund::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            if ($data->fund > 0) {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Berhasil menghapus data tipe dana, total dana lebih dari 0");
            }

            $delete =  Fund::findOrFail($id);
            if ($delete->delete()) {
                return redirect()->route('dashboard.master-data.funds')->with('success', "Berhasil menghapus data tipe dana");
            } else {
                return redirect()->route('dashboard.master-data.funds')->with('failed', "Gagal menghapus data tipe dana");
            }
        } else {
            return view('dashboard.master-data.funds')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function funds_finance(Request $request)
    {
        $data = Fund::where('company_id', Auth::user()->company_id)->get();
        $total_fund = 0;
        foreach ($data as $item) {
            $total_fund += (int)$item->fund;
        }
        $data_history = HistoryFund::query()->where('company_id', '=', Auth::user()->company_id)->where('datetime', 'like', "%$request->search%")->orderBy('datetime', 'desc')->paginate(10);
        return view('dashboard.finance.funds.index', ['data' => $data, 'data_history' => $data_history, 'total_fund' => $total_fund]);
    }

    public function funds_finance_create()
    {
        $funds = Fund::where('company_id', Auth::user()->company_id)->get();
        return view("dashboard.finance.funds.create", ['funds' => $funds]);
    }

    public function funds_finance_post(Request $request)
    {
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'amount' => 'required',
                'from_type' => 'required',
                'to_type' => 'required',
                'datetime' => 'required',
            ]);

            $validate['amount'] = (int) str_replace('.', '', $validate['amount']);

            $fund_form = Fund::where(
                "company_id",
                Auth::user()->company_id
            )->where('type', $validate['from_type'])->first();

            $fund_form->update([
                "fund" => $fund_form->fund - $validate['amount']
            ]);

            $fund_to = Fund::where(
                "company_id",
                Auth::user()->company_id
            )->where('type', $validate['to_type'])->first();

            $fund_to->update([
                "fund" => $fund_to->fund + $validate['amount']
            ]);

            HistoryFund::create([
                'company_id' => Auth::user()->company_id,
                'from_type' => $validate['from_type'],
                'to_type' => $validate['to_type'],
                'amount' => $validate['amount'],
                'datetime' => $validate['datetime'],
            ]);

            DB::commit();
            return redirect()->route('dashboard.finance.funds')->with('success', "Berhasil melakukan pengalihan alokasi dana");
        } catch (Throwable $error) {
            DB::rollBack();
            return redirect()->route('dashboard.finance.funds.create')->with('failed', "Gagal melakukan pengalihan alokasi dana");
        }
    }
}
