<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return response()->json([
            'users' => $users,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function getRole()
    {
        $roles = Role::select('name')->get();

        $roles_array = [];

        foreach ($roles as $role)
        {
            $roles_array[] = $role->name;
        }

        return response()->json([
            'roles' => $roles_array,
            'status_code' => 200
        ],Response::HTTP_OK);
    }

    public function store(UserRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Role
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);


                $user = User::create($input);

                $user->assignRole($request->roles);

                DB::commit();

                return response()->json([
                    'message' => 'User Added Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }

    public function edit($id)
    {
        $user = User::where('id',$id)->first();

        $user_role = DB::table('users')
            ->select(
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'model_has_roles.role_id as role_id',
                'roles.name as role_name'
            )
            ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
            ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
            ->where('users.id',$id)
            ->get();

        $ur_array = [];

        foreach ($user_role as $ur)
        {
            $ur_array[] = $ur->role_name;
        }

        return response()->json([
            'user' => $user,
            'userRoles' => $ur_array,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Update Users
                $input = $request->all();

                $user = User::findOrFail($id);
                $user->update($input);
                DB::table('model_has_roles')->where('model_id',$id)->delete();

                $role = explode(',', $request->input('roles'));

                $user->assignRole($role);


                DB::commit();

                return response()->json([
                    'message' => 'User Updated Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return \response()->json([
            'message' => 'user Deleted Successfully',
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function changePassword(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Role
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);


                $user = User::findOrFail($id);
                $user->update($input);

                DB::commit();

                return response()->json([
                    'message' => 'User Password updated Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
}
