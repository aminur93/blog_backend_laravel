<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Tag;
use Illuminate\Http\Request;
use DB;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id','desc')->get();

        return response()->json([
            'tags' => $tags,
            'status_code' => 200
        ],200);
    }

    public function store(TagRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Tag

                $tag = new Tag();
                $tag->tag_name = $request->tag_name;

                $tag->save();

                DB::commit();

                return response()->json([
                    'message' => 'Tag Added Successfully'
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
        $tag = Tag::findOrFail($id);

        return response()->json([
            'tag' => $tag,
            'status_code' => 200
        ],200);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Update Tag

                $tag = Tag::findOrFail($id);
                $tag->tag_name = $request->tag_name;

                $tag->save();

                DB::commit();

                return response()->json([
                    'message' => 'Tag Updated Successfully'
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
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'message' => 'Tag Deleted Successfully',
            'status_code' => 200
        ],200);
    }
}
