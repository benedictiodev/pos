<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
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

    public function history()
    {
        $data = Presence::with(['user'])->where("user_id", "!=", Auth::user()->id)->where("company_id", Auth::user()->company_id)->get();
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

        $history = User::with(['presence'])->where("id", "=", Auth::user()->id)->where("company_id", Auth::user()->company_id)->first();

        $setting = CompanySetting::query()->where("company_id", '=', Auth::user()->company_id)->first();

        return view('dashboard.presence.user_presence', [
            "user" => $user,
            "history" => $history,
            "setting" => $setting,
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
