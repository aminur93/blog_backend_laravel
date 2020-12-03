<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permission = Permission::latest()->get();

        return response()->json([
            'permissions' => $permission,
            'status_code' => 200
        ],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Permission

               $permission = new Permission();

               $permission->name = $request->name;

               $permission->save();

                DB::commit();

                return response()->json([
                    'message' => 'Permission Added Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],500);
            }
        }
    }

    public function edit($id)
    {
        $edit_permission = Permission::findOrFail($id);

        return response()->json([
            'edit_permission' => $edit_permission,
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Permission

                $permission = Permission::findOrFail($id);

                $permission->name = $request->name;

                $permission->save();

                DB::commit();

                return response()->json([
                    'message' => 'Permission Updated Successfully'
                ],200);

            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],500);
            }
        }
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return \response()->json([
            'message' => 'Permission Deleted Successfully',
            'status_code'=> 200
        ], Response::HTTP_OK);
    }
}
