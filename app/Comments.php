<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Comments extends Model
{
    public static function AllComments($id)
    {
        $blog_comments = DB::table('comments')
                        ->select(
                            'comments.id as id',
                            'comments.name as name',
                            'comments.email as email',
                            'comments.message as message',
                            'comments.created_at as created_at'
                        )
                        ->join('blog_posts','comments.blog_id','=','blog_posts.id')
                        ->where('blog_posts.id',$id);

        return $blog_comments;
    }
}
