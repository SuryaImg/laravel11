<?php

namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class UserController extends Controller
{
   
    public function index(Request $request)
    {
        try {
            $data = User::with('media')->latest()->paginate(5);
            return view('users.index',compact('data'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function create()
    {
        try {
            $roles = Role::pluck('name','name')->all();
            return view('users.create',compact('roles'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:250',
            'email' => 'required|email|max:250|unique:users,email',
            'password' => 'required|max:250|same:confirm_password',
            'roles' => 'required'
        ]);

        DB::beginTransaction();
        try {
        
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
        
            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            DB::commit();
        
            toastr()->addSuccess('User created successfully.');
            return redirect()->route('users.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
        // return redirect()->route('users.index')->with('success','User created successfully');
    }
    
    public function show($id)
    {
        try {
            $user = User::find($id);
            return view('users.show',compact('user'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }
    
    public function edit($id)
    {
        try {
            $user = User::find($id);
            $roles = Role::pluck('name','name')->all();
            $userRole = $user->roles->pluck('name','name')->all();
        
            return view('users.edit',compact('user','roles','userRole'));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:250',
            'email' => 'required|max:250|email|unique:users,email,'.$id,
            'password' => 'same:confirm_password|max:250',
            'roles' => 'required'
        ]);
        DB::beginTransaction();
        try {        
            $input = $request->all();
            if(!empty($input['password'])){ 
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));    
            }
        
            $user = User::find($id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
        
            $user->assignRole($request->input('roles'));
            DB::commit();
        
            return redirect()->route('users.index')
                            ->with('success','User updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }
    
    public function destroy($id)
    {
        try {
            User::find($id)->delete();
            toastr()->addSuccess('User deleted successfully.');
            return redirect()->route('users.index');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return redirect()->route('users.index')
            ->with('error', 'Something went wrong');
        }
    }
}