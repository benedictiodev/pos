<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function user_index(Request $request)
    {
        return view('dashboard.management-user.user.index', [
            "data" => User::query()
                ->where("company_id", Auth::user()->company_id)
                ->where("id", '!=', 1)
                ->where("name", "LIKE", "%$request->search%")
                ->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function user_create()
    {
        return view('dashboard.management-user.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "username" => "required|unique:users",
            "name" => "required",
            "email" => "required|unique:users|email",
            "password" => "required|confirmed",
        ]);

        $store = User::create([
            "username" => $request["username"],
            "name" => $validate["name"],
            "email" => $validate["email"],
            "password" => $validate["password"],
            "phone_number" => $request["phone_number"],
            "address" => $request["address"],
            "company_id" => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route("dashboard.management-user.user.index")->with('success', "Successfully to create a user");
        } else {
            return redirect()->route("dashboard.management-user.user.index")->with('failed', "Failed to create a user");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function user_edit(string $id)
    {
        $user = User::query()->where("company_id", Auth::user()->company_id)->where("id", "=", $id)->first();
        if ($user) {
            return view('dashboard.management-user.user.edit', [
                "user" => $user
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function user_update(Request $request, string $id)
    {
        $user = User::query()->where("company_id", "=", Auth::user()->company_id)->where("id", "=", $id)->first();

        $validate = $request->validate([
            "username" => "required|unique:users,username," . $user->id,
            "name" => "required",
            "email" => "required|email|unique:users,email," . $user->id,
            // "password" => "required|confirmed",
        ]);


        $store = $user->update([
            "username" => $request["username"],
            "name" => $validate["name"],
            "email" => $validate["email"],
            // "password" => $validate["password"],
            "phone_number" => $request["phone_number"],
            "address" => $request["address"],
            "company_id" => Auth::user()->company_id,
        ]);

        if ($store) {
            return redirect()->route("dashboard.management-user.user.index")->with('success', "Successfully to update a user");
        } else {
            return redirect()->route("dashboard.management-user.user.index")->with('failed', "Failed to update a user");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function user_destroy(string $id)
    {
        $user = User::query()->where("company_id", "=", Auth::user()->company_id)->where("id", "=", $id)->first();
        if ($user) {
            if ($user->id == Auth::user()->id) {
                return redirect()->route("dashboard.management-user.user.index")->with('failed', "Cannot to delete a user");
            }
            $delete = $user->delete();
            if ($delete) {
                return redirect()->route("dashboard.management-user.user.index")->with('success', "Successfully to delete a user");
            } else {
                return redirect()->route("dashboard.management-user.user.index")->with('failed', "Failed to delete a user");
            }
        } else {
            return abort(404);
        }
    }
}
