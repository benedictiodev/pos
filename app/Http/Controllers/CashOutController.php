<?php

namespace App\Http\Controllers;

use App\Models\CashOut;
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

        if ($store) {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('success', "Successfully to create cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('failed', "Failed to create cash out");
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

        if ($update) {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('success', "Successfully to update cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('failed', "Failed to update cash out");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete =  CashOut::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('success', "Successfully to delete cash out");
        } else {
            return redirect()->route('dashboard.finance.cash-flow-daily')->with('failed', "Failed to delete cash out");
        }
    }
}
