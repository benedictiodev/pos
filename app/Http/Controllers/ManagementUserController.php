<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManagementUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function rolesFunction($data_permission, $with_permission = []) {
        $array_result = array();
        foreach ($data_permission AS $item) {
            $checked = false;
            if (count($with_permission) > 0) {
                $index_checked = array_search($item->name, $with_permission);
                if ($index_checked !== false) {
                    $checked = true;
                }
            }

            $find = false;
            $menu = explode("-", $item->name);
            foreach ($array_result as $key => $search_item) {
                if ($search_item->menu == $menu[0]) {
                    $find = $key;
                    break;
                }
            }

            if ($find === false) {
                array_push($array_result, (object) [
                    'menu' => $menu[0],
                    'sub_menu' => array((object) [
                        'sub_menu' => $menu[1],
                        'permission' => array((object) [
                            'code' => $menu[2],
                            'checked' => $checked,
                        ]),
                    ]),
                ]);
            } else {
                $find_sub = false;
                foreach ($array_result[$find]->sub_menu as $key => $search_item) {
                    if ($search_item->sub_menu == $menu[1]) {
                        $find_sub = $key;
                        break;
                    }
                }

                if ($find_sub === false) {
                    array_push($array_result[$find]->sub_menu, (object) [
                        'sub_menu' => $menu[1],
                        'permission' => array((object) [
                            'code' => $menu[2],
                            'checked' => $checked,
                        ]),
                    ]);
                } else {
                    array_push($array_result[$find]->sub_menu[$find_sub]->permission, (object) [
                        'code' => $menu[2],
                        'checked' => $checked,
                    ]);
                }
            }
        }

        return $array_result;
    }


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
        $roles = Role::where('company_id', Auth::user()->company_id)->get();
        return view('dashboard.management-user.user.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function user_store(Request $request)
    {
        $validate = $request->validate([
            "username" => "required|unique:users",
            "name" => "required",
            "role_id" => "required",
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

        $role = Role::where('id', $validate['role_id'])->first();
        $store->assignRole($role);

        if ($store) {
            return redirect()->route("dashboard.management-user.user.index")->with('success', "Berhasil menambahkan akun pengguna baru");
        } else {
            return redirect()->route("dashboard.management-user.user.index")->with('failed', "Gagal menambahkan akun pengguna baru");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function user_edit(string $id)
    {
        $roles = Role::where('company_id', Auth::user()->company_id)->get();
        $user = User::query()->where("company_id", Auth::user()->company_id)->where("id", "=", $id)->first();
        $role_id_user = null;
        if ($user->getRoleNames()->first()) {
            $user_role = Role::where('company_id', Auth::user()->company_id)->where('name', $user->getRoleNames()->first())->first();
            $role_id_user = $user_role->id;
        }

        if ($user) {
            return view('dashboard.management-user.user.edit', [
                "user" => $user,
                "roles" => $roles,
                "user_role" => $role_id_user,
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
            "role_id" => "required",
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

        $role = Role::where('id', $validate['role_id'])->first();
        $user->syncRoles($role->name);

        if ($store) {
            return redirect()->route("dashboard.management-user.user.index")->with('success', "Berhasil memperbarui akun pengguna");
        } else {
            return redirect()->route("dashboard.management-user.user.index")->with('failed', "Gagal memperbarui akun pengguna");
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
                return redirect()->route("dashboard.management-user.user.index")->with('failed', "User tidak dapat dihapus");
            }
            $delete = $user->delete();
            if ($delete) {
                return redirect()->route("dashboard.management-user.user.index")->with('success', "Berhasil menghapus akun pengguna");
            } else {
                return redirect()->route("dashboard.management-user.user.index")->with('failed', "Gagal menghapus akun pengguna");
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function role_index(Request $request)
    {
        return view('dashboard.management-user.role.index', [
            "data" => Role::query()
                ->whereNot("name", "Owner-Pro")
                ->where("name", "LIKE", "%$request->search%")
                ->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function role_create() {
        $array_result = $this->rolesFunction(Permission::all());

        return view('dashboard.management-user.role.create', [
            "permission" => $array_result,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function role_store(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|unique:roles",
        ]);

        $store = Role::create([
            "company_id" => Auth::user()->company_id,
            "name" => $validate["name"],
            "is_superadmin" => false,
        ]);

        $store->givePermissionTo($request->permission);

        if ($store) {
            return redirect()->route("dashboard.management-user.role.index")->with('success', "Successfully to create a user");
        } else {
            return redirect()->route("dashboard.management-user.role.index")->with('failed', "Failed to create a user");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function role_edit(string $id)
    {
        $role = Role::query()->findOrFail($id);
        if ($role) {
            $data_permission = $role->permissions->pluck('name')->toArray();
            $array_result = $this->rolesFunction(Permission::all(), $data_permission);
                
            return view('dashboard.management-user.role.edit', [
                "role" => $role,
                "permission" => $array_result
            ]);
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function role_update(Request $request, string $id)
    {
        $role = Role::where("id", $id)->first();
        $role->syncPermissions($request->permission);

        if ($role) {
            return redirect()->route("dashboard.management-user.role.index")->with('success', "Successfully to update a role");
        } else {
            return redirect()->route("dashboard.management-user.role.index")->with('failed', "Failed to update a role");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function role_destroy(string $id)
    {
        $role = Role::where("id", $id)->first();
        if ($role) {
            $delete = $role->delete();
            if ($delete) {
                return redirect()->route("dashboard.management-user.role.index")->with('success', "Successfully to delete a role");
            } else {
                return redirect()->route("dashboard.management-user.role.index")->with('failed', "Failed to delete a role");
            }
        } else {
            return abort(404);
        }
    }
}
