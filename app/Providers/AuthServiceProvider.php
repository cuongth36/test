<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('dashboard',function($user){
            return $user->hasAccess('dashboard');
        });
        Gate::define('admin',function($user){
         return $user->hasAccess('admin');
       });
        // Trang
        Gate::define('page-list',function($user){
            return $user->hasAccess('danh-sach-trang');
        });

        Gate::define('page-create',function($user){
            return $user->hasAccess('them-trang');
        });

        Gate::define('page-edit',function($user){
            return $user->hasAccess('sua-trang');
        });

        Gate::define('page-delete',function($user){
            return $user->hasAccess('xoa-trang');
        });


        //Post
    
        Gate::define('post-list', function ($user) {
           return $user->hasAccess('danh-sach-bai-viet');
        });

        Gate::define('post-create', function ($user) {
            return $user->hasAccess('them-bai-viet');
         });

         Gate::define('post-edit', function ($user) {
            return $user->hasAccess('sua-bai-viet');
         });

         Gate::define('post-delete', function ($user) {
            return $user->hasAccess('xoa-bai-viet');
         });
        
         // Category post
         Gate::define('post-category-list', function ($user) {
            return $user->hasAccess('danh-sach-danh-muc-bai-viet');
         });

         Gate::define('post-category-create', function ($user) {
            return $user->hasAccess('them-moi-danh-muc-bai-viet');
         });

         Gate::define('post-category-edit', function ($user) {
            return $user->hasAccess('sua-danh-muc-bai-viet');
         });

         Gate::define('post-category-delete', function ($user) {
            return $user->hasAccess('xoa-danh-muc-bai-viet');
         });

         // Product
         Gate::define('product-list', function ($user) {
            return $user->hasAccess('danh-sach-san-pham');
         });

         Gate::define('product-create', function ($user) {
            return $user->hasAccess('them-san-pham');
         });

         Gate::define('product-edit', function ($user) {
            return $user->hasAccess('sua-san-pham');
         });

         Gate::define('product-delete', function ($user) {
            return $user->hasAccess('xoa-san-pham');
         });

         // Product category
         Gate::define('product-category-list', function ($user) {
            return $user->hasAccess('danh-sach-danh-muc-san-pham');
         });

         Gate::define('product-category-create', function ($user) {
            return $user->hasAccess('them-moi-danh-muc-san-pham');
         });

         Gate::define('product-category-edit', function ($user) {
            return $user->hasAccess('sua-danh-muc-san-pham');
         });

         Gate::define('product-category-delete', function ($user) {
            return $user->hasAccess('xoa-danh-muc-san-pham');
         });

         // Product color

         Gate::define('product-color-list', function ($user) {
            return $user->hasAccess('danh-sach-mau-san-pham');
         });

         Gate::define('product-color-create', function ($user) {
            return $user->hasAccess('them-mau-san-pham');
         });

         Gate::define('product-color-edit', function ($user) {
            return $user->hasAccess('sua-mau-san-pham');
         });

         Gate::define('product-color-delete', function ($user) {
            return $user->hasAccess('xoa-mau-san-pham');
         });

         //Product size

         Gate::define('product-size-list', function ($user) {
            return $user->hasAccess('danh-sach-size-san-pham');
         });

         Gate::define('product-size-create', function ($user) {
            return $user->hasAccess('them-size-san-pham');
         });

         Gate::define('product-size-edit', function ($user) {
            return $user->hasAccess('sua-size-san-pham');
         });

         Gate::define('product-size-delete', function ($user) {
            return $user->hasAccess('xoa-size-san-pham');
         });

         //Ban hang

         Gate::define('order-list', function ($user) {
            return $user->hasAccess('danh-sach-don-hang');
         });

         Gate::define('order-edit', function ($user) {
            return $user->hasAccess('sua-don-hang');
         });

         Gate::define('order-delete', function ($user) {
            return $user->hasAccess('xoa-don-hang');
         });

         Gate::define('inventory-statistics', function ($user) {
            return $user->hasAccess('thong-ke-hang-ton');
         });

         Gate::define('revenue-statistics', function ($user) {
            return $user->hasAccess('thong-ke-doanh-thu');
         });

         // Menu
         Gate::define('menu-list', function ($user) {
            return $user->hasAccess('danh-sach-menu');
         });

         Gate::define('menu-create', function ($user) {
            return $user->hasAccess('them-menu');
         });

         Gate::define('menu-edit', function ($user) {
            return $user->hasAccess('sua-menu');
         });

         Gate::define('menu-delete', function ($user) {
            return $user->hasAccess('xoa-menu');
         });

         //Slider 

         Gate::define('slider-list', function ($user) {
            return $user->hasAccess('danh-sach-slider');
         });

         Gate::define('slider-create', function ($user) {
            return $user->hasAccess('them-slider');
         });

         Gate::define('slider-edit', function ($user) {
            return $user->hasAccess('sua-slider');
         });

         Gate::define('slider-delete', function ($user) {
            return $user->hasAccess('xoa-slider');
         });


         // User

         Gate::define('user-list', function ($user) {
            return $user->hasAccess('danh-sach-user');
         });

         Gate::define('user-create', function ($user) {
            return $user->hasAccess('them-user');
         });

         Gate::define('user-edit', function ($user) {
            return $user->hasAccess('sua-user');
         });

         Gate::define('user-delete', function ($user) {
            return $user->hasAccess('xoa-user');
         });

         // Role

         Gate::define('role-list', function ($user) {
            return $user->hasAccess('danh-sach-quyen');
         });

         Gate::define('role-create', function ($user) {
            return $user->hasAccess('them-moi-quyen');
         });

         Gate::define('role-edit', function ($user) {
            return $user->hasAccess('sua-quyen');
         });

         Gate::define('role-delete', function ($user) {
            return $user->hasAccess('xoa-quyen');
         });

         //Action 

         Gate::define('action-user', function ($user) {
            return $user->hasAccess('action-user');
         });

         Gate::define('action-don-hang', function ($user) {
            return $user->hasAccess('action-don-hang');
         });

         Gate::define('action-san-pham', function ($user) {
            return $user->hasAccess('action-san-pham');
         });

         Gate::define('action-mau-san-pham', function ($user) {
            return $user->hasAccess('action-mau-san-pham');
         });

         Gate::define('action-bai-viet', function ($user) {
            return $user->hasAccess('action-bai-viet');
         });

         Gate::define('action-trang', function ($user) {
            return $user->hasAccess('action-trang');
         });

         Gate::define('action-size', function ($user) {
            return $user->hasAccess('action-size');
         });
         

    }
}
