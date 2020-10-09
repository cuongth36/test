<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    
    function detail($slug){
        $slug = str_replace_last('.html', '', $slug);
        $db_select = DB::select("SELECT pages.* FROM pages where pages.slug = '$slug' ");
        $slug_new = '';
        foreach ($db_select as $item){
           $slug_new = $item->slug;
        }
        if(count($db_select) > 0){
            $category_info = Page::where('slug', '=', $slug_new)->get();
            $id = 0;
            foreach($category_info as $item){
                $id = $item->id;
            }
            $page = Page::find($id);
            return view('pages.page.detail', compact('page'));
        }else{
            return view('errors.404');
        }
    }

}
