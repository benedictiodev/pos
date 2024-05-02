<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
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

        if ($store) {
            return redirect()->route('dashboard.finance.cash-in')->with('success', "Successfully to create cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-in')->with('failed', "Failed to create cash in");
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

        if ($update) {
            return redirect()->route('dashboard.finance.cash-in')->with('success', "Successfully to update cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-in')->with('failed', "Failed to update cash in");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete =  CashIn::destroy($id);
        if ($delete) {
            return redirect()->route('dashboard.finance.cash-in')->with('success', "Successfully to delete cash in");
        } else {
            return redirect()->route('dashboard.finance.cash-in')->with('failed', "Failed to delete cash in");
        }
    }
}
