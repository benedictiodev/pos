<?php

namespace App\Http\Controllers;

use App\Models\RemaskCashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemarksCashFlowController extends Controller
{
    public function index(Request $request)
    {
        $data = RemaskCashFlow::query()->where('company_id', '=', Auth::user()->company_id)
            ->where(function ($query) use ($request) {
                $query->where("name", 'like', "%$request->search%");
            })->paginate(10);
        return view('dashboard.master-data.remarks-cash-flow.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('dashboard.master-data.remarks-cash-flow.create');
    }

    public function store(Request $request)
    {
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
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Berhasil menambahkan data keterangan");
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Gagal menambahkan data keterangan");
        }
    }

    public function edit($id)
    {
        $data = RemaskCashFlow::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            return view("dashboard.master-data.remarks-cash-flow.edit", ["data" => $data]);
        } else {
            return redirect()->route("dashboard.master-data.remarks-cash-flow")->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function update(Request $request, $id)
    {
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
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Berhasil memperbarui data keterangan");
            } else {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Gagal memperbarui data keterangan");
            }
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }

    public function destroy($id)
    {
        $data = RemaskCashFlow::findOrFail($id);
        if ($data && $data->company_id == Auth::user()->company_id) {
            $delete =  RemaskCashFlow::destroy($id);
            if ($delete) {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('success', "Berhasil menghapus data keterangan");
            } else {
                return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', "Gagal menghapus data keterangan");
            }
        } else {
            return redirect()->route('dashboard.master-data.remarks-cash-flow')->with('failed', 'Ups! Sepertinya Anda mengikuti tautan yang buruk. Jika menurut Anda ini adalah masalah kami, beri tahu kami.');
        }
    }
}
