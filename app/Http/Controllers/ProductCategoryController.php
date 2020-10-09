<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCategories;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductCategoryController extends Controller
{
    function __construct()
    {
        
    }

    function show(Request $request){
       $order_by = '';
        if($request->input('order_by')){
            $name = $request->input('order_by');
            switch ($name) {
                case 'asc':
                    $order_by = 'order by title asc';
                   
                    break;
                case 'desc':
                    $order_by = 'order by title desc';
                    break;
                case 'price_asc': 
                    $order_by = 'order by price asc';
                    break;
                case 'price_desc':
                    $order_by = 'order by price desc';
                    break;
            }
        }
           
        $where = "products.status = '1' AND products.deleted_at IS NULL $order_by";
        $limit = 8;
        $products = DB::select("SELECT * FROM products where $where limit $limit");
        $product_number = DB::select("SELECT * FROM products where $where");
       
        return view('pages.product.product', compact('products', 'product_number', 'limit'));
    }

    function productFillter(Request $request){
        
        $min = $request->input('min');
        $max = $request->input('max');
        $limit = 8;
        $request = $request->input('request');
        if(!empty($request)){
            $slug = str_replace_last('.html', '', $request);
            $db_select = DB::select("SELECT product_categories.* FROM product_categories where product_categories.slug = '$slug' ");
            $slug_new = '';
          
            foreach ($db_select as $item){
                $slug_new = $item->slug;
            } 
           
            if(count($db_select) >0){
                $products_cate = ProductCategories::where('slug', '=' , $slug_new)->get();
                $id = 0;
                foreach($products_cate as $cate){
                    $id = $cate->id;
                }
                $parent = ' ';
                $parent_id = '';

                foreach($db_select as $item){
                    $parent = $item->parent_id;
                    $parent_id = $item->id;
                }
               
                $list_child_cate = DB::select("SELECT id FROM product_categories WHERE parent_id = $parent_id ");
                $child_id_cate = [];
                foreach($list_child_cate as $child){
                    $child_id_cate[] = $child->id;
                }
                $child_id_cate = implode(",", $child_id_cate);

                if($parent == 0){
                    $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id IN ($child_id_cate) and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit");

                    $product_fillter = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id IN ($child_id_cate) and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id");
                }else{
                    $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id = $id and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit");

                    $product_fillter = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id = $id and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id");
                }

                
            }
        }else{

            $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price
            , max(prd.discount) as discount,max(prd.product_hot) as product_hot FROM products as prd WHERE  prd.status= '1'
             and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit");

             $product_fillter = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price
             , max(prd.discount) as discount,max(prd.product_hot) as product_hot FROM products as prd WHERE  prd.status= '1' and prd.deleted_at IS NULL
              and prd.price BETWEEN $min AND $max group by prd.id");
        }
        
        ?>
            <div class="container">
            <div class="row result-load-more-fillter result-load-more-zz">
                <?php 
                    if(!empty($products)){
                        foreach($products as $item)
                        {
                        ?>
                         <div class="col-xl-3 col-md-6 col-lg-4 col-sm-12 col-12 layout ">
                                <div class="box-item box-item-list-shoppage">
                                    <div class="box-item-image">
                                        <a href="<?php echo route('product.detail', [$item->slug, '.html']) ?>"><img src="<?php echo url($item->thumbnail) ?>" alt="<?php echo $item->title ?>"></a>
                                    </div>
                                    <div class="box-item-info">
                                        <a href="<?php echo route('product.detail', [$item->slug, '.html']) ?>"><h3 class="item-name m-bottom-0"><?php echo $item->title ?></h3></a>
                                        <div class="item-price-rate">
                                        <div class="item-price">
                                            <?php 
                                                if(!empty($item->discount)){
                                                    ?>
                                                           
                                                        <span class="cost"><?php echo number_format($item->price, 0, '', '.')?>đ</span>    
                                                             
                                                        <span class="sale"> <?php echo number_format((1-(5/100))*$item->price, 0, '', '.')?>đ</span>
                                                               
                                                    <?php
                                                    
                                                }else{
                                                   ?>
                                                   <span class="price"><?php echo number_format($item->price, 0, '', '.')?>đ</span>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                        </div>
                                                                                                                        
                                    </div>
                                    <div class="offer">
                                        <?php
                                            if(!empty($item->discount)){
                                               
                                                ?>
                                                <div class='percent'>-<?php echo $item->discount ?>%</div>
                                            <?php
                                            }

                                            if($item->product_hot == '1'){
                                                
                                                ?>
                                                <div class="percent-hot">Hot</div>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                          </div> 
                    <?php
                    }
                }
                ?>
               
            </div>
                <?php
                
                    if(count($product_fillter) > $limit){
                        ?>
                        <div class="row">
                            <div class="load-more-fillter">
                                <input type="hidden" name="_token" id="csrf-token" value="<?php echo Session::token() ?>" />
                                 <input type="hidden" name="action_load_more" class="action-load-more" value="<?php echo route('product.fillter.loadmore') ?>">
                                 <?php 
                                    if(!empty($request)){
                                        $slug = str_replace_last('.html', '', $request);
                                        ?>
                                         <input type="hidden" name="request-fillter" class="request-fillter" value="<?php echo $slug; ?>">
                                    <?php
                                    }
                                 ?>
                                 <input type="hidden" class="fillter-min" value="<?php echo $min ?>">
                                 <input type="hidden" class="fillter-max" value="<?php echo $max ?>">
                                <input type="hidden" id="total_product_fillter" value="<?php echo count($product_fillter) ?>">
                                <button class="btn btn-primary load-more-item">Xem thêm</button>
                            </div>
                        </div>
                    <?php
                    }
                ?> 
            </div>
           
        <?php
    }

    function productFillterLoadmore(Request $request){
        $min = $request->input('min');
        $max = $request->input('max');
        $slug =  $request->input('slug');
        $page = $request->input('page') ? (int) $request->input('page') : 1;
        $limit = 8;
        $offset = ($page - 1) * $limit;
        if(!empty( $slug)){
            $slug = str_replace_last('.html', '', $slug);
            $db_select = DB::select("SELECT product_categories.* FROM product_categories where product_categories.slug = '$slug' ");
            $slug_new = '';
            foreach ($db_select as $item){
            $slug_new = $item->slug;
            } 
            if(count($db_select) >0){
                $products_cate = ProductCategories::where('slug', '=' , $slug_new)->get();
                $id = 0;
                foreach($products_cate as $cate){
                    $id = $cate->id;
                }

                $parent = ' ';
                $parent_id = '';

                foreach($db_select as $item){
                    $parent = $item->parent_id;
                    $parent_id = $item->id;
                }
               
                $list_child_cate = DB::select("SELECT id FROM product_categories WHERE parent_id = $parent_id ");
                $child_id_cate = [];
                foreach($list_child_cate as $child){
                    $child_id_cate[] = $child->id;
                }
                $child_id_cate = implode(",", $child_id_cate);
                if($parent == 0){
                    $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id IN ($child_id_cate) and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit offset $offset");
                }else{
                    $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price,
                    max(prd.product_hot) as product_hot, max(prd.discount) as discount
                    FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                    where product_categories.id = $id and prd.status = '1' and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit offset $offset");
                }
            }
        }else{
            $products = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail, max(prd.price) as price
            , max(prd.discount) as discount,max(prd.product_hot) as product_hot FROM products as prd WHERE  prd.status= '1'
             and prd.deleted_at IS NULL and prd.price BETWEEN $min AND $max group by prd.id limit $limit offset $offset");
        }
       
        ?>
        <div class="container">
            <div class="row  ">
                <?php 
                    if(!empty($products)){
                        foreach($products as $item)
                        {
                        ?>
                        <div class="col-xl-3 col-md-6 col-lg-4 col-sm-12 col-12 layout ">
                                <div class="box-item box-item-list-shoppage">
                                    <div class="box-item-image">
                                        <a href="<?php echo route('product.detail', [$item->slug, '.html']) ?>"><img src="<?php echo url($item->thumbnail) ?>" alt="<?php echo $item->title ?>"></a>
                                    </div>
                                    <div class="box-item-info">
                                        <a href="<?php echo route('product.detail', [$item->slug, '.html']) ?>"><h3 class="item-name m-bottom-0"><?php echo $item->title ?></h3></a>
                                        <div class="item-price-rate">
                                        <div class="item-price">
                                            <?php 
                                                if(!empty($item->discount)){
                                                    ?>
                                                        
                                                        <span class="cost"><?php echo number_format($item->price, 0, '', '.')?>đ</span>    
                                                            
                                                        <span class="sale"> <?php echo number_format((1-(5/100))*$item->price, 0, '', '.')?>đ</span>
                                                            
                                                    <?php
                                                    
                                                }else{
                                                ?>
                                                <span class="price"><?php echo number_format($item->price, 0, '', '.')?>đ</span>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                        </div>
                                                                                                                        
                                    </div>
                                    <div class="offer">
                                        <?php
                                            if(!empty($item->discount)){
                                            
                                                ?>
                                                <div class='percent'>-<?php echo $item->discount ?>%</div>
                                            <?php
                                            }

                                            if($item->product_hot == '1'){
                                                
                                                ?>
                                                <div class="percent-hot">Hot</div>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                        </div> 
                    <?php
                    }
                }
                ?>
            
            </div>
           
        </div>
       
    <?php
   }

    function loadMore(Request $request){
        $page = $request->input('page') ? (int) $request->input('page') : 1;
        $list_product = Product::all()->count();
        $limit = 8;
        $total_page = ceil($list_product/$limit);
        $offset = ($page - 1) * $limit;
        $products = DB::select("SELECT * FROM products where products.status = '1' and products.deleted_at IS NULL limit $limit offset $offset");
        return view('pages.product.data-product-list', compact('products'));

    }

    function loadMoreProductOfCate(Request $request, $slug){
        $slug = str_replace_last('.html', '', $slug);
        $db_select = DB::select("SELECT product_categories.* FROM product_categories where product_categories.slug = '$slug' ");
        $slug_new = '';
        foreach ($db_select as $item){
           $slug_new = $item->slug;
        } 
        if(count($db_select) >0){
            $products_cate = ProductCategories::where('slug', '=' , $slug_new)->get();
            $id = 0;
            $parent = ' ';
            $parent_id = '';
            foreach($products_cate as $cate){
                $id = $cate->id;
            }
          
            foreach($products_cate as $item){
                $parent = $item->parent_id;
                $parent_id = $item->id;
            }
           
            $list_child_cate = DB::select("SELECT id FROM product_categories WHERE parent_id = $parent_id ");
            $child_id_cate = [];
            foreach($list_child_cate as $child){
                $child_id_cate[] = $child->id;
            }
            $child_id_cate = implode(",", $child_id_cate);
            $page = $request->input('page') ? (int) $request->input('page') : 1;
            $list_product =ProductCategories::find($id)->product;
            $limit = 8;
            $total_page = ceil(count( $list_product)/$limit);
            $offset = ($page - 1) * $limit;
            if($parent == 0){
                $list_category = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount
                FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                where product_categories.id IN ($child_id_cate) and prd.status = '1' and prd.deleted_at IS NULL group by prd.id limit $limit offset $offset");
            }else{
                $list_category = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount
                FROM product_categories inner join products as prd on product_categories.id = prd.category_id
                where product_categories.id = $id and prd.status = '1' and prd.deleted_at IS NULL group by prd.id limit $limit offset $offset");
            }
           
            return view('pages.product.data-product-list', compact('list_category'));
        }
    }

    function productOfCategory(Request $request, $slug){
     
        $slug = str_replace_last('.html', '', $slug);
        $db_select = DB::select("SELECT product_categories.* FROM product_categories where product_categories.slug = '$slug' ");
        $slug_new = '';
        foreach ($db_select as $item){
           $slug_new = $item->slug;
        }

        if(count($db_select) >0){
            $products_cate = ProductCategories::where('slug', '=' , $slug_new)->get();
            $id = 0;
            $parent = ' ';
            $parent_id = '';
            foreach($products_cate as $cate){
                $id = $cate->id;
            }

            foreach($products_cate as $item){
                $parent = $item->parent_id;
                $parent_id = $item->id;
            }
           
            $list_child_cate = DB::select("SELECT id FROM product_categories WHERE parent_id = $parent_id ");
            $child_id_cate = [];
            foreach($list_child_cate as $child){
                $child_id_cate[] = $child->id;
            }

            $order_by = '';
            if($request->input('order_by')){
                $name = $request->input('order_by');
                switch ($name) {
                    case 'asc':
                        $order_by = 'order by products.title asc';
                    
                        break;
                    case 'desc':
                        $order_by = 'order by products.title desc';
                        break;
                    case 'price_asc': 
                        $order_by = 'order by products.price asc';
                        break;
                    case 'price_desc':
                        $order_by = 'order by products.price desc';
                        break;
                }
            }
            $limit = 8;
          
            $child_id_cate = implode(",", $child_id_cate);
           
            if($parent == 0){
               
                $where = "product_categories.id IN ($child_id_cate) AND prd.status = '1' and prd.deleted_at IS NULL group by prd.id  $order_by";
                $list_category = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount
                FROM product_categories inner join products as prd on product_categories.id = prd.category_id where $where  limit $limit");
                $product_number_of_cate = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount
                FROM product_categories inner join products as prd on product_categories.id = prd.category_id where $where");
              
            }else{
                $where = "product_categories.id = $id AND prd.status = '1' and prd.deleted_at IS NULL group by prd.id  $order_by";
                $list_category = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount 
                FROM product_categories inner join products as prd on product_categories.id = prd.category_id where $where  limit $limit");
                $product_number_of_cate = DB::select("SELECT max(prd.title) as title, max(prd.slug) as slug, max(prd.thumbnail) as thumbnail,max(prd.product_hot) as product_hot, max(prd.price) as price, max(prd.discount) as discount
                 FROM product_categories inner join products as prd on product_categories.id = prd.category_id where $where");
            }
          
            return view('pages.product.product', compact('list_category', 'product_number_of_cate' , 'limit'));
        }else{
            return redirect('404.html');
        }

    }
}
