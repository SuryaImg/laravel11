<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Throwable;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(['permission:role-list|role-create|role-edit|role-delete'], only: ['index']),
            new Middleware(['permission:role-create'], only: ['create', 'store']),
            new Middleware(['permission:role-edit'], only: ['edit', 'update']),
            new Middleware(['permission:role-delete'], only: ['destroy']),
        ];
    }
    
    public function index(Request $request)
    {
        try {
            $roles = Role::orderBy('id', 'DESC')->paginate(5);
            return view('roles.index', compact('roles'));
        } catch (\Throwable $e) {
            //throw $th;
            // Log the error or handle it as needed
            Log::error($e->getMessage());
    
            // Return a custom error response

            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function create()
    {
        try {
            $permission = Permission::get();
            return view('roles.create', compact('permission'));
        } catch (\Throwable $e) {
            //throw $th;
            // Log the error or handle it as needed
            Log::error($e->getMessage());
    
            // Return a custom error response

            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:250',
            'permission' => 'nullable',
        ]);

        DB::beginTransaction();

        try {

            $role = Role::create(['name' => $request->input('name')]);
            // dd($request->input('permission'));
            $permissions = [];
            $post_permissions = $request->input('permission');
            foreach ($post_permissions as $key => $val) {
                $permissions[intval($val)] = intval($val);
            }
            $role->syncPermissions($permissions);
    
            DB::commit();

            toastr()->addSuccess('Role created successfully.');
            return redirect()->route('roles.index');
    
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function show($id)
    {
        try {
            $role = Role::find($id);
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)
                ->get();
    
            return view('roles.show', compact('role', 'rolePermissions'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        try {
            $role = Role::find($id);
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
    
            return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:250',
            'permission' => 'nullable',
        ]);

        DB::beginTransaction();

        try {    
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();
            // dd($request->input('permission'));
            $permissions = [];
            $post_permissions = $request->input('permission');
            foreach ($post_permissions as $key => $val) {
                $permissions[intval($val)] = intval($val);
            }
            $role->syncPermissions($permissions);
            DB::commit();
    
            toastr()->addSuccess('Role updated successfully.');
            return redirect()->route('roles.index');
    
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function destroy($id)
    {
        try {
            DB::table("roles")->where('id', $id)->delete();
            toastr()->addSuccess('Role deleted successfully.');
            return redirect()->route('roles.index');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('roles.index')
            ->with('error', 'Something went wrong');
        }
    }
}