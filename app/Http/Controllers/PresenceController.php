<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode = Carbon::now()->format('Y-m-d');
        if ($request->periode) {
            $periode = $request->periode;
        }
        $data = User::with(['presence' => function ($query) use ($periode) {
            $query->where('created_at', 'like', "%$periode%")->limit(1);
        }])->where('users.id', '!=', 1)->where("company_id", Auth::user()->company_id)->paginate(10);
        return view('dashboard.presence.index', [
            "data" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'company_id' => 'required'
        ]);

        $store = Presence::create([
            'user_id' => $request->user_id,
            'company_id' => $request->company_id
        ]);

        if ($store) {
            return redirect()->route('dashboard.presence.index')->with('success', "Successfully to user presence.");
        } else {
            return redirect()->route('dashboard.presence.index')->with('failed', "Failed to user presence.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presence $presence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        //
    }

    public function history(Request $request)
    {
        $periode = Carbon::now()->format('Y-m');
        if ($request->periode) {
            $periode = $request->periode;
        }
        $data = User::with(['presence' => function ($query) use ($periode) {
            $query->where("created_at", "like", "%$periode%");
        }])->where('users.id', '!=', 1)->where("company_id", Auth::user()->company_id)->get();
        return view('dashboard.presence.history', [
            "data" => $data
        ]);
    }

    public function presence_user()
    {
        $periode = Carbon::now()->format('Y-m-d');
        $user = User::with(['presence' => function ($query) use ($periode) {
            $query->where("created_at", "like", "%$periode%");
        }])->where("id", "=", Auth::user()->id)->where("company_id", Auth::user()->company_id)->first();
        return view('dashboard.presence.user_presence', [
            "user" => $user
        ]);
    }

    public function presence_user_store(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required',
        ]);

        $user = User::query()->findOrFail($validate['id']);

        $store = Presence::create([
            'user_id' => $validate['id'],
            'company_id' => $user->company_id
        ]);

        if ($store) {
            return redirect()->route('dashboard.presence.presence_user')->with('success', "Successfully to user presence.");
        } else {
            return redirect()->route('dashboard.presence.presence_user')->with('failed', "Failed to user presence.");
        }
    }
}
