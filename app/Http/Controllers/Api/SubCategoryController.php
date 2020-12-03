<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\SubCategory;
use Illuminate\Http\Request;
use DB;

class SubCategoryController extends Controller
{
    public function index()
    {
        $sub_categories = DB::table('sub_categories')
                            ->select(
                                'sub_categories.id as id',
                                'sub_categories.sub_cat_name as sub_cat_name',
                                'sub_categories.category_id as category_id',
                                'categories.category_name as category_name'
                            )
                            ->join('categories','sub_categories.category_id','=','categories.id')
                            ->orderBy('sub_categories.id','desc')
                            ->get();
        return response()->json([
            'sub_categories' => $sub_categories,
            'status_code' => 200
        ],200);
    }

    public function store(SubCategoryRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Sub Category

                $subcategory = new SubCategory();
                $subcategory->category_id = $request->category_id;
                $subcategory->sub_cat_name = $request->sub_cat_name;

                $subcategory->save();

                DB::commit();

                return response()->json([
                    'message' => 'Sub Category Added Successfully'
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
        $sub_category = DB::table('sub_categories')
            ->select(
                'sub_categories.id as id',
                'sub_categories.sub_cat_name as sub_cat_name',
                'sub_categories.category_id as category_id'
            )
            ->join('categories','sub_categories.category_id','=','categories.id')
            ->where('sub_categories.id', $id)
            ->first();

        return response()->json([
            'sub_category' => $sub_category,
            'status_code' => 200
        ],200);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Update Sub Category

                $subcategory = SubCategory::findOrFail($id);

                $subcategory->category_id = $request->category_id;
                $subcategory->sub_cat_name = $request->sub_cat_name;

                $subcategory->save();

                DB::commit();

                return response()->json([
                    'message' => 'Sub Category Updated Successfully'
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
        $subCategory = SubCategory::findOrfail($id);
        $subCategory->delete();

        return response()->json([
            'message' => 'Sub Category Deleted Successfully',
            'status_code' => 200
        ],200);
    }
}
