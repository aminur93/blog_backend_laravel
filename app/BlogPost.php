<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BlogPost extends Model
{
    protected $guarded = [];

    public static function blogs()
    {
        $blogposts = DB::table('blog_posts')
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
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->orderBy('blog_posts.id','desc');

        return $blogposts;
    }

    public static function single_Blog($id)
    {
        $singleblogposts = DB::table('blog_posts')
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
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->where('blog_posts.id',$id);

        return $singleblogposts;
    }

    public static function CategoryBlog($category_id)
    {
        $category_blog = DB::table('blog_posts')
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
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->where('blog_posts.category_id',$category_id);

        return $category_blog;
    }

    public static function SubCategoryBlog($sub_cat_id)
    {
        $subCategoryBlog =  DB::table('blog_posts')
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
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->where('blog_posts.sub_cat_id',$sub_cat_id);

        return $subCategoryBlog;
    }

    public static function TagBlog($tag_id)
    {
        $tagblog = DB::table('blog_posts')
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
                'blog_posts.blog_date as blog_date',
                'blog_posts.status as status',
                'blog_posts.publish as publish',
                'blog_posts.feature as feature'
            )
            ->join('categories','blog_posts.category_id','=','categories.id')
            ->join('sub_categories','blog_posts.sub_cat_id','=','sub_categories.id')
            ->join('tags','blog_posts..tag_id','=','tags.id')
            ->join('users','blog_posts.author_id','=','users.id')
            ->where('blog_posts.tag_id',$tag_id);

        return $tagblog;
    }

}
