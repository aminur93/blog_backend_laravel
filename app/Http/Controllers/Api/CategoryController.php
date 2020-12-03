<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        $catgeory = Category::orderBy('id','desc')->get();

        return response()->json([
            'category' => $catgeory,
            'status_code' => 200
        ],200);
    }

    public function store(CategoryRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Category

                $category = new Category();
                $category->category_name = $request->category_name;

                $category->save();

                DB::commit();

                return response()->json([
                    'message' => 'Category Added Successfully'
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
        $category = Category::findOrfail($id);

        return response()->json([
            'edit_category_data' => $category,
            'status_code' => 200
        ],200);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Update Category

                $category = Category::findOrFail($id);
                $category->category_name = $request->category_name;

                $category->save();

                DB::commit();

                return response()->json([
                    'message' => 'Category Updated Successfully'
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
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json([
            'message' => 'Category Deleted Successfully',
            'status_code' => 200
        ],200);
    }
}
