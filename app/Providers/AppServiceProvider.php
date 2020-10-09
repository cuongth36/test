<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Menu;
use App\ProductCategories;
use URL;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        URL::forceScheme('https');
        View::composer('header/header', function ($view) {
            $menus = Menu::where('parent_id', '=', '0')->orderBy('menu_sort', 'asc')->get();
            $view->with('menus', $menus);
        });

        
        view()->composer('pages/product/product', function ($view) {
            $product_max_price = DB::select("SELECT  MAX(price) as max_price FROM products");
            $product_min_price = DB::select("SELECT  MIN(price) as min_price FROM products");
    
            $price_max = 0; 
            foreach ($product_max_price as $item){
                $price_max = $item->max_price;
            }
    
            $price_min = 0; 
            foreach ($product_min_price as $item){
                $price_min =   $item->min_price;
            }
            
            function data_tree($data, $parent_id = 0, $level =0){
                $result = [];
                foreach($data as $item){
                    if($item['parent_id'] == $parent_id){
                        $item['level'] = $level; 
                        $result[] = $item;
                        $child =data_tree($data, $item['id'], $level + 1); 
                        $result = array_merge($result, $child);
                    }
                }
                return $result;
            }
            $product_price = [$price_min, $price_max];
            $categories = ProductCategories::all();
            $categories = data_tree($categories);
            $view->with('categories', $categories);
            $view->with('product_price',$product_price );
        });
    }
}
