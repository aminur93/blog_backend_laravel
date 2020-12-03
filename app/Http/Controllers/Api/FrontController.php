<?php

namespace App\Http\Controllers\Api;

use App\BlogPost;
use App\Category;
use App\Comments;
use App\ContactUs;
use App\Http\Controllers\Controller;
use App\SubCategory;
use App\Tag;
use Illuminate\Http\Request;
use DB;

class FrontController extends Controller
{
    public function getAllCategory()
    {
        $category = Category::latest()->get();

        return response()->json([
            'categories' => $category,
            'status_code' => 200
        ],200);
    }

    public function getAllSubCategory()
    {
        $subCategory = SubCategory::latest()->get();

        return response()->json([
            'sub_categories' => $subCategory,
            'status_code' => 200
        ],200);
    }

    public function getAllTag()
    {
        $tag = Tag::latest()->get();

        return response()->json([
            'tags' => $tag,
            'status_code' => 200
        ],200);
    }

    public function getRecentBlog()
    {
        $blog = BlogPost::latest()->get()->take(3);

        return response()->json([
            'recent_blog' => $blog,
            'status_code' => 200
        ],200);
    }

    public function getPopularBlog()
    {
        $blog = BlogPost::where('view_count','>=',50)->latest()->get()->take(3);

        return response()->json([
            'popular_blog' => $blog,
            'status_code' => 200
        ],200);
    }

    public function getMainBlog()
    {
        $blog_list  = BlogPost::blogs()->paginate(5);

        return response()->json([
            'blogPosts' => $blog_list,
            'status_code' => 200
        ],200);
    }

    public function BlogViewUpdate(Request $request)
    {
        $id = $request->id;
        $view_count = $request->view_count + 1;

        BlogPost::where('id', $id)->update(['view_count' => $view_count]);


         return response()->json([
             'message' => 'View Count Updated Successfully',
             'status_code' => 200
         ],200);
    }

    public function singleBlog($id)
    {
        $singleBlogList = BlogPost::single_Blog($id)->first();

        return response()->json([
            'single_blog' => $singleBlogList,
            'status_code' => 200
        ],200);
    }

    public function BlogComments(Request $request,$id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Comments

                $comment = new Comments();

                $comment->blog_id = $id;
                $comment->name = $request->name;
                $comment->email = $request->email;
                $comment->message = $request->message;

                $comment->save();

                DB::commit();

                return response()->json([
                    'message' => 'Comments Added Successfully'
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

    public function getBlogComment(Request $request)
    {
        $id = $request->id;

        $commentList = Comments::AllComments($id)->get();

        return response()->json([
            'comments_list' => $commentList,
            'status_code' => 200
        ],200);
    }

    public function getCategoryBlog($category_id)
    {
        $categoryBlog = BlogPost::CategoryBlog($category_id)->paginate(5);

        return response()->json([
            'categoryBlog' => $categoryBlog,
            'status_code' => 200
        ],200);
    }

    public function getSubCategoryBlog($sub_cat_id)
    {
        $subCategoryBlog = BlogPost::SubCategoryBlog($sub_cat_id)->paginate(5);

        return response()->json([
            'SubCategoryBlog' => $subCategoryBlog,
            'status_code' => 200
        ],200);
    }

    public function getTagBlog($tag_id)
    {
        $tagBlog = BlogPost::TagBlog($tag_id)->paginate(5);

        return response()->json([
            'tagBlog' => $tagBlog,
            'status_code' => 200
        ],200);
    }

    public function getBlogSearchList(Request $request)
    {
        $search = $request->search;

        $searchBlog = BlogPost::select('id','title')->orderBy('id','desc');

        if ($search)
        {
            $searchBlog->where(function ($searchBlog) use($search){
                $searchBlog->where('title','like','%'.$search.'%');
            });
        }

        $searchList = $searchBlog->get();

        return response()->json([
            'searchBlog' => $searchList,
            'status_code' => 200
        ],200);
    }

    public function getBlogSearch(Request $request)
    {
        $searchValue = $request->input('search');

        $searchAllBlog = DB::table('blog_posts')
            ->select(
                'blog_posts.id as id',
                'categories.category_name as category_name',
                'sub_categories.sub_cat_name as sub_cat_name',
                'tags.tag_name as tag_name',
                'users.name as name',
                'blog_posts.title as title',
                'blog_posts.description as description',
                'blog_posts.image as image',
                'blog_posts.view_count as view_count',
                'blog_posts.blog_date as blog_date'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->orderBy('id', 'desc');

        if ($searchValue)
        {
            $searchAllBlog->where(function ($searchAllBlog) use($searchValue){
                $searchAllBlog->where('title','like','%'.$searchValue.'%');
            });
        }

        $result = $searchAllBlog->paginate(5);

        return response()->json([
            'searchListBlog' => $result,
            'status_code' => 200
        ],200);
    }

    public function contactUs(Request $request)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                // Step 1 : Create Contact

                $contact = new ContactUs();

                $contact->name = $request->name;
                $contact->email = $request->email;
                $contact->message = $request->message;

                $contact->save();

                DB::commit();

                return response()->json([
                    'message' => 'Message Sent Successfully'
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
}
