<?php

namespace App\Http\Controllers\Api;

use App\BlogPost;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogposts = BlogPost::blogs()->get();

        return response()->json([
            'blogPosts' => $blogposts,
            'status_code' => 200
        ],200);
    }

    public function getSubCategory($category_id)
    {
        $subCategory = DB::table('sub_categories')
            ->select(
                'sub_categories.id as id',
                'sub_categories.sub_cat_name as sub_cat_name'
            )
            ->Join('categories','sub_categories.category_id','=','categories.id')
            ->where('sub_categories.category_id', $category_id)
            ->get();

        return response()->json(['subcategory' => $subCategory], 200);
    }

    public function store(BlogPostRequest $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : create Blog Posts

                $blogpost = new BlogPost();

                $blogpost->category_id = $request->category_id;
                $blogpost->sub_cat_id = $request->sub_cat_id;
                $blogpost->tag_id = $request->tag_id;
                $blogpost->author_id = $request->author_id;
                $blogpost->title = $request->title;
                $blogpost->description = $request->description;

                if($request->hasFile('image')){

                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;

                        $original_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                        $large_image_path = public_path().'/assets/uploads/large/'.$filename;
                        $medium_image_path = public_path().'/assets/uploads/medium/'.$filename;
                        $small_image_path = public_path().'/assets/uploads/small/'.$filename;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(1920,680)->save($large_image_path);
                        Image::make($image_tmp)->resize(1000,529)->save($medium_image_path);
                        Image::make($image_tmp)->resize(500,529)->save($small_image_path);

                        $blogpost->image = $filename;
                    }
                }



                $blogpost->view_count = 0;
                $blogpost->blog_date = $request->blog_date;

                if ($request->status == true)
                {
                    $blogpost->status = 1;
                }else{
                    $blogpost->status = 0;
                }

                if ($request->publish == true)
                {
                    $blogpost->publish = 1;
                }else{
                    $blogpost->publish = 0;
                }

                if ($request->feature == true)
                {
                    $blogpost->feature = 1;
                }else{
                    $blogpost->feature = 0;
                }


                $blogpost->save();

                DB::commit();

                return response()->json([
                    'message' => 'Blog Post Added Successfully'
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
        $blogpost = DB::table('blog_posts')
            ->select(
                'blog_posts.id as id',
                'blog_posts.category_id as category_id',
                'blog_posts.sub_cat_id as sub_cat_id',
                'blog_posts.tag_id as tag_id',
                'blog_posts.author_id as author_id',
                'blog_posts.title as title',
                'blog_posts.description as description',
                'blog_posts.image as image',
                'blog_posts.view_count as view_count',
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature',
                'sub_categories.category_id as sub_category_id'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->where('blog_posts.id',$id)
            ->first();

        return response()->json([
            'blogPost' => $blogpost,
            'status_code' => 200
        ],200);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post'))
        {

            if($request->hasFile('image')){

                $image_tmp = $request->file('image');

                if($image_tmp->isValid()){
                    $extenson = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extenson;

                    $original_image_path = public_path().'/assets/uploads/original_image/'.$filename;
                    $large_image_path = public_path().'/assets/uploads/large/'.$filename;
                    $medium_image_path = public_path().'/assets/uploads/medium/'.$filename;
                    $small_image_path = public_path().'/assets/uploads/small/'.$filename;

                    //Resize Image
                    Image::make($image_tmp)->save($original_image_path);
                    Image::make($image_tmp)->resize(1920,680)->save($large_image_path);
                    Image::make($image_tmp)->resize(1000,529)->save($medium_image_path);
                    Image::make($image_tmp)->resize(500,529)->save($small_image_path);

                }
            }else{
                $filename = $request->current_image ;
            }

            $blogpost = BlogPost::findOrFail($id);

            $blogpost->category_id = $request->category_id;
            $blogpost->sub_cat_id = $request->sub_cat_id;
            $blogpost->tag_id = $request->tag_id;
            $blogpost->author_id = $request->author_id;
            $blogpost->title = $request->title;
            $blogpost->description = $request->description;
            $blogpost->image = $filename;
            $blogpost->view_count = 0;
            $blogpost->blog_date = $request->blog_date;

            if ($request->status){
                $blogpost->status = $request->status == "false" ? 0 : 1;
            }

            if ($request->publish){
                $blogpost->publish = $request->publish == "false" ? 0 : 1;
            }

            if ($request->feature){
                $blogpost->feature = $request->feature == "false" ? 0 : 1;
            }


            $blogpost->save();


            return response()->json([
                'message' => 'Blog Post Updated Successfully'
            ],200);

        }
    }

    public function destroy($id)
    {
        $blog = BlogPost::findOrFail($id);

        if($blog->image)
        {
            $original_path = public_path().'/assets/uploads/original_image/'.$blog->image;
            $large_path = public_path().'/assets/uploads/large/'.$blog->image;
            $medium_path = public_path().'/assets/uploads/medium/'.$blog->image;
            $small_path = public_path().'/assets/uploads/small/'.$blog->image;

            unlink($original_path);
            unlink($large_path);
            unlink($medium_path);
            unlink($small_path);
        }


        $blog->delete();

        return response()->json([
            'message' => 'Blog Post Deleted Successfully',
            'status_code' => 200
        ],200);
    }

    public function deleteImage($id)
    {
        $blog = BlogPost::findOrFail($id);

        if($blog->image)
        {
            $original_path = public_path().'/assets/uploads/original_image/'.$blog->image;
            $large_path = public_path().'/assets/uploads/large/'.$blog->image;
            $medium_path = public_path().'/assets/uploads/medium/'.$blog->image;
            $small_path = public_path().'/assets/uploads/small/'.$blog->image;

            unlink($original_path);
            unlink($large_path);
            unlink($medium_path);
            unlink($small_path);
        }

        $blog->update(['image' => null]);

        return response()->json([
            'updateBlog' => $blog,
            'status_code' => 200
        ],200);
    }

    public function approve($id)
    {
       BlogPost::where('id',$id)->update(['status' => 1]);

        return response()->json([
            'status_code' => 200
        ],200);
    }

    public function unapprove($id)
    {
        BlogPost::where('id',$id)->update(['status' => 0]);

        return response()->json([
            'status_code' => 200
        ],200);
    }

    public function publish($id)
    {
        BlogPost::where('id',$id)->update(['publish' => 1]);

        return response()->json([
            'status_code' => 200
        ],200);
    }

    public function unpublish($id)
    {
        BlogPost::where('id',$id)->update(['publish' => 0]);

        return response()->json([
            'status_code' => 200
        ],200);
    }

    public function feature($id)
    {
        BlogPost::where('id',$id)->update(['feature' => 1]);

        return response()->json([
            'status_code' => 200
        ],200);
    }

    public function Notfeature($id)
    {
        BlogPost::where('id',$id)->update(['feature' => 0]);

        return response()->json([
            'status_code' => 200
        ],200);
    }
}
