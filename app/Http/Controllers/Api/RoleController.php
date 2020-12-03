<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        $role = DB::table('roles')
            ->select(
                'roles.id as id',
                'roles.name as role_name',
                DB::raw('group_concat(permissions.name) as permission_name')
            )
            ->join('role_has_permissions','roles.id','=','role_has_permissions.role_id')
            ->join('permissions','role_has_permissions.permission_id','=','permissions.id')
            ->groupBy('role_has_permissions.role_id')
            ->orderBy('roles.id','desc')
            ->get();


        return response()->json([
            'roles' => $role,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Role
                //$role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

                $role = new Role();

                $role->name = $request->name;

                $role->save();

                //$permissions = explode(',',$request->permission);

                $role->syncPermissions($request->input('permission'));

                DB::commit();

                return response()->json([
                    'message' => 'Role Added Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();

                $error = $e->getMessage();

                return response()->json([
                    'message' => $error
                ],500);
            }
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
