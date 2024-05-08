<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view("dashboard.finance.cash-in.create");
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
        ]);

        $store = CashIn::create([
            'company_id' => 1,
            'fund' => $validate['fund'],
            'remark' => $validate['remark'],
            'datetime' => $validate['datetime'],
        ]);

        $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

        if ($store) {
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
        return view('dashboard.finance.cash-in.edit', ["data" => $data]);
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
        ]);

        $data = CashIn::findOrFail($id);

        $update = $data->update([
            'company_id' => 1,
            'fund' => $validate['fund'],
            'remark' => $validate['remark'],
            'datetime' => $validate['datetime'],
        ]);

        $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

        if ($update) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to update cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to update cash in");
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

        $delete =  CashIn::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to delete cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to delete cash in");
        }
    }
}
