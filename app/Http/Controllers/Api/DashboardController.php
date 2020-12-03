<?php

namespace App\Http\Controllers\Api;

use App\BlogPost;
use App\Category;
use App\Http\Controllers\Controller;
use App\SubCategory;
use App\Tag;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function countCategory()
    {
        $category_count = Category::get()->count();

        return response()->json([
            'category_count' => $category_count,
            'status_code' => 200
        ],200);
    }

    public function countSubCategory()
    {
        $sub_category_count = SubCategory::get()->count();

        return response()->json([
            'sub_category_count' => $sub_category_count,
            'status_code' => 200
        ],200);
    }

    public function countTag()
    {
        $tag_count = Tag::get()->count();

        return response()->json([
            'tag_count' => $tag_count,
            'status_code' => 200
        ],200);
    }

    public function countBlog()
    {
        $blog_count = BlogPost::get()->count();

        return response()->json([
            'blog_count' => $blog_count,
            'status_code' => 200
        ],200);
    }

    public function getCategory()
    {
        $categoryList = Category::latest()->get()->take(5);

        return response()->json([
            'category_list' => $categoryList,
            'status_code' => 200
        ],200);
    }

    public function getTag()
    {
        $tagList = Tag::latest()->get()->take(5);

        return response()->json([
            'tag_list' => $tagList,
            'status_code' => 200
        ],200);
    }

    public function getBlog()
    {
        $blogList = BlogPost::latest()->get()->take(5);

        return response()->json([
            'blog_list' => $blogList,
            'status_code' => 200
        ],200);
    }
}
