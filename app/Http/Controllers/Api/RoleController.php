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
        $role = Role::latest()->get();

        $permissionList = DB::table('permissions')
                ->select(
                    'permissions.id as id',
                    'permissions.name as permission_name',
                    'role_has_permissions.role_id as role_id'
                )
                ->join('role_has_permissions','permissions.id','=','role_has_permissions.permission_id')
                ->get();


        $role_permission = [];

        foreach($role as $key => $r)
        {
            $role_permission[$key]['role_name'] = $r->name;
            $role_permission[$key]['id'] = $r->id;

           foreach ($permissionList as $pl)
           {
               if ($r->id == $pl->role_id)
               {
                   $role_permission[$key]['permission_name'][] = $pl->permission_name;

               }

           }
        }


        return response()->json([
            'roles' => $role_permission,
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
        $role = Role::findOrFail($id);

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $rp_array = [];
        foreach ($rolePermissions as $rp)
        {
            $rp_array[] = $rp;
        }



        return response()->json([
            'role' => $role,
            'rolePermission' => $rp_array,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Role
                //$role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

                $role = Role::findOrFail($id);

                $role->name = $request->name;

                $role->save();

                //$permissions = explode(',',$request->permission);

                $role->syncPermissions($request->input('permission'));

                DB::commit();

                return response()->json([
                    'message' => 'Role Updated Successfully'
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

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        DB::table('role_has_permissions')->where('role_id',$id)->delete();

        $role->delete();

        return response()->json([
            'message' => 'Role Deleted Successfully',
            'status_code'=> 200
        ], Response::HTTP_OK);
    }
}
