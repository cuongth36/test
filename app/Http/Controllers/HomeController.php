<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\CategoryPost;
use App\OrderDetail;
use App\ProductCategories;
use App\Post;
use App\Slider;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list_sliders= Slider::orderBy('id', 'asc')->get();
        $products = Product::where('status', '=', '1')->take(5)->get();
        $cate_prd_id = [];
        $category_product = ProductCategories::where('parent_id', '=', 0)->take(3)->get();
        $post_category = CategoryPost::take(3)->get();
        $post = Post::take(3)->where('status', 'approve')->get();
        $product_hot =Product::where('status', '1')->where('product_hot', '1')->get();
        $product_seller = DB::select("SELECT max(products.id) as product_id ,max(products.title) as product_title, max(products.slug) as product_slug, max(products.price) as product_price, max(products.discount) as product_discount, max(products.thumbnail) as product_thumbnail, max(products.product_hot) as product_hot, SUM(order_details.qty) AS quantity FROM order_details inner JOIN products on order_details.product_id = products.id  GROUP by product_id ORDER BY quantity DESC");
        
        return view ('pages.homepage.home', compact('list_sliders', 'products','product_seller','post_category', 'post', 'product_hot', 'category_product'));
    }

    function search(Request $request){
        $key_word = $request->input('s');
        if(empty($key_word)){
           
            return redirect('/');
        }else{
            $products = Product::where('title', '=', $key_word)->where('status', '=', '1')->paginate(8);
            return view ('pages.search.list', compact('products'));
        }
       
    }

    
}
