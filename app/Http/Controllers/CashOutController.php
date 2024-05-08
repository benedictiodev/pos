<?php

namespace App\Http\Controllers;

use App\Models\CashOut;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CashOut::paginate(5);
        return view('dashboard.finance.cash-out.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.finance.cash-out.create");
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

        $store = CashOut::create([
            'company_id' => 1,
            'fund' => $validate['fund'],
            'remark' => $validate['remark'],
            'datetime' => $validate['datetime'],
        ]);

        $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

        if ($store) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to create cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to create cash out");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CashOut::findOrFail($id);
        return view('dashboard.finance.cash-out.edit', ["data" => $data]);
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

        $data = CashOut::findOrFail($id);

        $update = $data->update([
            'company_id' => 1,
            'fund' => $validate['fund'],
            'remark' => $validate['remark'],
            'datetime' => $validate['datetime'],
        ]);

        $query_data = ['periode' => Carbon::parse($validate['datetime'])->format('Y-m-d')];

        if ($update) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to update cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to update cash out");
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

        $delete =  CashOut::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('success', "Successfully to delete cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily', $query_data)->with('failed', "Failed to delete cash out");
        }
    }
}
