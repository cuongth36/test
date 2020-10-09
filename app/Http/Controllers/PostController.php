<?php

namespace App\Http\Controllers;

use App\CategoryPost;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    function show(){
        $posts = Post::where('status', '=', 'approve')->paginate(8);
        $product_seller = DB::select("SELECT SUM(ord.qty) as total_qty, max(prd.title) as title , max(prd.id) as id ,max(prd.slug) as slug , max(prd.thumbnail) as thumbnail , max(prd.price) as price , max(ord.product_id) as product_id FROM order_details as ord INNER JOIN products as prd on ord.product_id = prd.id GROUP BY ord.product_id ORDER BY total_qty desc limit 5 ");
        return view('pages.post.list', compact('posts', 'product_seller'));
    }

    function postOfCategory($slug){
        $slug = str_replace_last('.html', '', $slug);
        $db_select = DB::select("SELECT category_posts.* FROM category_posts where category_posts.slug = '$slug' ");
        $slug_new = '';
        foreach ($db_select as $item){
           $slug_new = $item->slug;
        }
        if(count($db_select) >0){
            $category = CategoryPost::all();
            $category_info = CategoryPost::where('slug', '=', $slug_new)->get();
            $id_cate = 0;
            foreach($category_info as $item){
                $id_cate = $item->id;
            }
            $list_post_of_category = CategoryPost::find($id_cate)->posts()->paginate(8);
            return view('pages.post.list', compact('category' , 'list_post_of_category'));
        }else{
            return view('errors.404');
        }

    }
    
    function detail($slug){
        $slug = str_replace_last('.html', '', $slug);
        $db_select = DB::select("SELECT posts.* FROM posts where posts.slug = '$slug' ");
        $slug_new = '';
        foreach ($db_select as $item){
           $slug_new = $item->slug;
        }
        if(count($db_select) > 0){
            $posts = Post::where('slug' , '=', $slug_new)->get();
           
            $product_seller = DB::select("SELECT SUM(ord.qty) as total_qty, max(prd.title) as title , max(prd.id) as id ,max(prd.slug) as slug , max(prd.thumbnail) as thumbnail , max(prd.price) as price , max(ord.product_id) as product_id FROM order_details as ord INNER JOIN products as prd on ord.product_id = prd.id GROUP BY ord.product_id ORDER BY total_qty desc limit 5 ");
            return view('pages.post.detail', compact('posts','product_seller'));
        }else{
            return view('errors.404');
        }
    }

    
}
