<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Auth::routes(
    [
        'register' => false
    ]
);
Route::get('/','HomeController@index')->name('homepage');



//Backend
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::middleware('auth')->group(function(){
    Route::get('dashboard','Admin\DashboardController@show')->middleware('can:dashboard');
    Route::get('admin','Admin\DashboardController@show')->middleware('can:admin');
    Route::prefix('admin')->group(function(){
        Route::prefix('user')->group(function(){
           
            Route::get('list', 'Admin\UserController@list')->name('user.list')->middleware('can:user-list');
            Route::get('list/pagination', 'Admin\UserController@pagination')->name('user.pagination');
            Route::get('search', 'Admin\UserController@search')->name('user.search');
            Route::get('add', 'Admin\UserController@add')->middleware('can:user-create');
            Route::post('store', 'Admin\UserController@store')->name('user.store')->middleware('can:user-create');
            Route::get('edit/{id}', 'Admin\UserController@edit')->name('user.edit');
            Route::post('update/{id}', 'Admin\UserController@update')->name('user.update');
            Route::get('delete/{id}', 'Admin\UserController@delete')->name('user.delete')->middleware('can:user-delete');
            Route::post('action', 'Admin\UserController@action')->name('user.action')->middleware('can:action-user');

        });

        Route::prefix('role')->group(function(){
            Route::get('list', 'Admin\RoleController@list')->name('role.list')->middleware('can:role-list');
            Route::get('list/pagination', 'Admin\RoleController@pagination')->name('role.pagination');
            Route::get('search', 'Admin\RoleController@search')->name('role.search');
            Route::get('add', 'Admin\RoleController@add')->middleware('can:role-create');
            Route::post('store', 'Admin\RoleController@store')->name('role.store');
            Route::get('edit/{id}', 'Admin\RoleController@edit')->name('role.edit')->middleware('can:role-edit');
            Route::post('update/{id}', 'Admin\RoleController@update')->name('role.update');
            Route::get('delete/{id}', 'Admin\RoleController@delete')->name('role.delete')->middleware('can:role-delete');

        });
        
        Route::prefix('page')->group(function(){
              // Route page
            Route::get('list', 'Admin\PageController@list')->name('page.list')->middleware('can:page-list');
            Route::get('list/pagination', 'Admin\PageController@pagination')->name('page.pagination');
            Route::get('search', 'Admin\PageController@search')->name('page.search');
            Route::get('add', 'Admin\PageController@add')->middleware('can:page-create');
            Route::get('edit/{id}', 'Admin\PageController@edit')->name('page.edit')->middleware('can:page-edit');
            Route::post('update/{id}', 'Admin\PageController@update')->name('page.update');
            Route::post('store', 'Admin\PageController@store')->name('page.store');
            Route::get('delete/{id}', 'Admin\PageController@delete')->name('page.delete')->middleware('can:page-delete');
            Route::post('action', 'Admin\PageController@action')->name('page.action')->middleware('can:action-trang');

        });
      
        Route::prefix('post')->group(function(){
             //Route category post
            Route::get('category/list', 'Admin\CategoryPostController@list')->name('category.list')->middleware('can:post-category-list');
            Route::get('category/add', 'Admin\CategoryPostController@add')->middleware('can:post-category-create');
            Route::post('category/store', 'Admin\CategoryPostController@store')->name('category.store');
            Route::get('category/search', 'Admin\CategoryPostController@search')->name('category.search');
            Route::get('category/edit/{id}', 'Admin\CategoryPostController@edit')->name('category.edit')->middleware('can:post-category-edit');
            Route::post('category/update/{id}', 'Admin\CategoryPostController@update')->name('category.update');
            Route::get('category/delete/{id}', 'Admin\CategoryPostController@delete')->name('category.delete')->middleware('can:post-category-delete');

            //Route post
            Route::get('list', 'Admin\PostController@list')->name('post.list')->middleware('can:post-list');
            Route::get('add', 'Admin\PostController@add')->middleware('can:post-create');
            Route::post('store', 'Admin\PostController@store')->name('post.store');
            Route::get('search', 'Admin\PostController@search')->name('post.search');
            Route::get('list/pagination', 'Admin\PostController@pagination')->name('post.pagination');
            Route::post('action', 'Admin\PostController@action')->name('post.action')->middleware('can:action-bai-viet');
            Route::get('edit/{id}', 'Admin\PostController@edit')->name('post.edit')->middleware('can:post-edit');
            Route::post('update/{id}', 'Admin\PostController@update')->name('post.update');
            Route::get('delete/{id}', 'Admin\PostController@delete')->name('post.delete')->middleware('can:post-delete');

        });
       
        
        Route::prefix('product')->group(function(){
            // category product
            Route::get('category/list', 'Admin\ProductCategoryController@list')->name('category_product.list')->middleware('can:product-category-list');
            Route::get('category/add', 'Admin\ProductCategoryController@add')->middleware('can:product-category-create');
            Route::post('category/store', 'Admin\ProductCategoryController@store')->name('category_product.store');
            Route::get('category/edit/{id}', 'Admin\ProductCategoryController@edit')->name('category_product.edit')->middleware('can:product-category-edit');
            Route::post('category/update/{id}', 'Admin\ProductCategoryController@update')->name('category_product.update');
            Route::get('category/delete/{id}', 'Admin\ProductCategoryController@delete')->name('category_product.delete')->middleware('can:product-category-delete');
            Route::get('category/search', 'Admin\ProductCategoryController@search')->name('category_product.search');


            // product
            Route::get('list', 'Admin\ProductController@list')->name('product.list');
            Route::get('add', 'Admin\ProductController@add')->middleware('can:product-create');
            Route::post('store', 'Admin\ProductController@store')->name('product.store');
            Route::get('edit/{id}', 'Admin\ProductController@edit')->name('product.edit')->middleware('can:product-edit');
            Route::post('update/{id}', 'Admin\ProductController@update')->name('product.update');
            Route::get('delete/{id}', 'Admin\ProductController@delete')->name('product.delete')->middleware('can:product-delete');
            Route::get('deletefile/{id}', 'Admin\ProductController@deleteFeatureImage')->name('product.deletefile');
            Route::get('search', 'Admin\ProductController@search')->name('product.search');
            Route::post('action', 'Admin\ProductController@action')->name('product.action')->middleware('can:action-san-pham');
            Route::get('list/pagination', 'Admin\ProductController@pagination')->name('product.pagination');


            // color product
            Route::get('color/list', 'Admin\ColorController@list')->name('color.list')->middleware('can:product-color-list');
            Route::get('color/add', 'Admin\ColorController@add')->name('color.add')->middleware('can:product-color-create');
            Route::post('color/store', 'Admin\ColorController@store')->name('color.store');
            Route::get('color/search', 'Admin\ColorController@search')->name('color.search');
            Route::get('color/edit/{id}', 'Admin\ColorController@edit')->name('color.edit')->middleware('can:product-color-edit');
            Route::post('color/update/{id}', 'Admin\ColorController@update')->name('color.update');
            Route::get('color/delete/{id}', 'Admin\ColorController@delete')->name('color.delete')->middleware('can:product-color-delete');
            Route::post('color/action', 'Admin\ColorController@action')->name('color.action')->middleware('can:action-mau-san-pham');
            Route::get('color/pagination', 'Admin\ColorController@pagination')->name('color.pagination');



             // size product
             Route::get('size/list', 'Admin\SizeController@list')->name('size.list')->middleware('can:product-size-list');
             Route::get('size/add', 'Admin\SizeController@add')->name('size.add')->middleware('can:product-size-create');
             Route::post('size/store', 'Admin\SizeController@store')->name('size.store');
             Route::get('size/search', 'Admin\SizeController@search')->name('size.search');
             Route::get('size/edit/{id}', 'Admin\SizeController@edit')->name('size.edit')->middleware('can:product-size-edit');
             Route::post('size/update/{id}', 'Admin\SizeController@update')->name('size.update');
             Route::get('size/delete/{id}', 'Admin\SizeController@delete')->name('size.delete')->middleware('can:product-size-delete');
             Route::post('size/action', 'Admin\SizeController@action')->name('size.action')->middleware('can:action-size-san-pham');
             Route::get('size/pagination', 'Admin\SizeController@pagination')->name('size.pagination');

             
           
        });

        // menu
        Route::prefix('menu')->group(function(){
            Route::get('list', 'Admin\MenuController@list')->name('menu.list')->middleware('can:menu-list');
            Route::get('add', 'Admin\MenuController@add')->middleware('can:menu-create');
            Route::post('store', 'Admin\MenuController@store')->name('menu.store');
            Route::get('edit/{id}', 'Admin\MenuController@edit')->name('menu.edit')->middleware('can:menu-edit');
            Route::post('update/{id}', 'Admin\MenuController@update')->name('menu.update');
            Route::get('delete/{id}', 'Admin\MenuController@delete')->name('menu.delete')->middleware('can:menu-delete');
            Route::get('search', 'Admin\MenuController@search')->name('menu.search');
        });

        // order
        Route::prefix('order')->group(function(){
            Route::get('list', 'Admin\OrderController@list')->name('order.list')->middleware('can:order-list');
            Route::get('edit/{id}', 'Admin\OrderController@edit')->name('order.edit')->middleware('can:order-edit');
            Route::post('update/{id}', 'Admin\OrderController@update')->name('order.update');
            Route::get('delete/{id}', 'Admin\OrderController@delete')->name('order.delete')->middleware('can:order-delete');
            Route::get('list/pagination', 'Admin\OrderController@pagination')->name('order.pagination');
            Route::get('search', 'Admin\OrderController@search')->name('order.search');
            Route::post('action', 'Admin\OrderController@action')->name('order.action')->middleware('can:action-don-hang');
            Route::get('inventory', 'Admin\InventoryController@list')->name('inventory.list')->middleware('can:inventory-statistics');
            Route::get('inventory/search', 'Admin\InventoryController@search')->name('inventory.search');
            Route::get('inventory/pagination', 'Admin\InventoryController@pagination')->name('inventory.pagination');
            Route::match(['get', 'post'], 'revenue', 'Admin\InventoryController@revenueStatistics')->name('revenue.statistics')->middleware('can:revenue-statistics');
            Route::post('revenue-sell','Admin\InventoryController@getData')->name('revenue.data');

        });

        //slider 
        Route::prefix('slider')->group(function(){

            Route::get('list', 'Admin\SliderController@list')->name('slider.list')->middleware('can:slider-list');
            Route::get('add', 'Admin\SliderController@add')->middleware('can:slider-create');
            Route::post('store', 'Admin\SliderController@store')->name('slider.store');
            Route::get('edit/{id}', 'Admin\SliderController@edit')->name('slider.edit')->middleware('can:slider-edit');
            Route::post('update/{id}', 'Admin\SliderController@update')->name('slider.update');
            Route::get('delete/{id}', 'Admin\SliderController@delete')->name('slider.delete')->middleware('can:slider-delete');
            Route::get('search', 'Admin\SliderController@search')->name('slider.search');
            Route::get('list/pagination', 'Admin\SliderController@pagination')->name('slider.pagination');

        });

        //Customer
        Route::prefix('customer')->group(function(){
            Route::get('list', 'Admin\CustomerController@list')->name('admin.customer.list');
            Route::get('list/pagination', 'Admin\CustomerController@pagination')->name('admin.customer.pagination');
            Route::get('list/search', 'Admin\CustomerController@search')->name('admin.customer.search');

            //Feedback
            Route::get('feedback', 'Admin\ContactController@list')->name('admin.feedback.list');
            Route::get('feedback/edit/{id}', 'Admin\ContactController@edit')->name('admin.feedback.edit');
            Route::post('feedback/update/{id}', 'Admin\ContactController@update')->name('admin.feedback.update');
            Route::get('feedback/delete/{id}', 'Admin\ContactController@delete')->name('admin.feedback.delete');
            Route::get('feedback/pagination', 'Admin\ContactController@pagination')->name('admin.feedback.pagination');
            Route::get('feedback/search', 'Admin\ContactController@search')->name('admin.feedback.search');
            Route::post('feedback/action', 'Admin\ContactController@action')->name('admin.feedback.action');
        });
    });
});

Route::get('404.html', function () {
    return view('errors.404');
});

//Cart 
Route::get('cart', 'CartController@list')->name('cart.list');
Route::get('cart/add/{id}','CartController@add')->name('cart.add');
Route::post('cart/update','CartController@update')->name('cart.update');
Route::get('delete/{id}', 'CartController@delete')->name('cart.delete');
Route::get('destroy', 'CartController@destroy')->name('cart.destroy');


//Check out
Route::get('pre-checkout', 'CartController@pre_checkout')->name('cart.pre_checkout');
Route::middleware('CheckLogin')->group(function(){
    Route::get('checkout', 'CartController@checkout')->name('cart.checkout');
    Route::post('bill', 'CartController@bill')->name('cart.bill');
    Route::get('dat-hang-thanh-cong.html', 'CartController@success');
});

//Customer 

Route::get('xac-thuc-email/kich-hoat-nguoi-dung', 'CustomerController@activeToken')->name('active.token');
Route::get('kich-hoat-thanh-cong.html', 'CustomerController@activeSuccess')->name('active.success');

Route::post('dang-ky-thanh-cong.html', 'CustomerController@store')->name('customer.store');
Route::get('dang-ky.html', 'CustomerController@register')->name('customer.register');

Route::post('check-login', 'CustomerController@checkLogin')->name('customer.checklogin');
Route::post('check-signin', 'CustomerController@checkSignin')->name('customer.signin');
Route::get('dang-nhap', 'CustomerController@login')->name('customer.login');
Route::get('dang-nhap.html', 'CustomerController@loginCustomer')->name('sign-in');
Route::middleware('CheckSignin')->group(function(){
    Route::get('khach-hang/thong-tin/{id}', 'CustomerController@profile')->name('profile');
    Route::post('customer/profile/{id}', 'CustomerController@update')->name('profile.update');
});

Route::get('dang-xuat.html', 'CustomerController@logout')->name('logout-customer');
Route::get('khoi-phuc-mat-khau.html', 'CustomerController@forgotPassword')->name('customer.forgot');
Route::post('khoi-phuc-mat-khau.html', 'CustomerController@checkEmailForgot')->name('customer.check-forgot');
Route::get('khoi-phuc-mau-khau/xac-nhan-mat-khau.html', 'CustomerController@viewForgotPassword')->name('customer.view-confirm');
Route::post('khoi-phuc-mau-khau/xac-nhan-mat-khau.html', 'CustomerController@forgotConfirm')->name('customer.confirm');
Route::get('huy-don-hang/{order}/{id}','CustomerController@updateOrderHistory')->name('order.history');
//Contact
Route::get('lien-he.html', 'ContactController@show');
Route::post('create', 'ContactController@store')->name('contact.create');

//List Product
Route::get('danh-muc', 'ProductCategoryController@show')->name('product.show');
Route::get('danh-muc/fillter', 'ProductCategoryController@productFillter')->name('product.fillter');
Route::post('danh-muc/fillter', 'ProductCategoryController@productFillterLoadmore')->name('product.fillter.loadmore');
Route::post('danh-muc/loadmore', 'ProductCategoryController@loadMore')->name('product.loadmore');
Route::post('danh-muc/{slug}','ProductCategoryController@loadMoreProductOfCate')->name('product.cate.loadmore');
Route::get('danh-muc/{slug}{extension}' , 'ProductCategoryController@productOfCategory')->name('product_category.show');

//Product detail
Route::get('san-pham/{slug}{extension}', 'ProductController@detail')->name('product.detail');
Route::post('san-pham/{slug}{extension}', 'ProductController@getColorId')->name('product.change');

//Post 
Route::get('tin-tuc.html', 'PostController@show')->name('post.show.list');
Route::get('tin-tuc/{slug}{extension}', 'PostController@detail')->name('post.detail');



// Page
Route::get('/{slug?}{extension}', 'PageController@detail')->name('page.detail');

//Search
Route::post('/tim-kiem', 'HomeController@search')->name('search.info');

