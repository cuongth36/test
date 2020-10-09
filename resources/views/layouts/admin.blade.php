<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- Scripts -->
     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/Chart.min.css')}}">
    <title>Admintrator</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="{{route('homepage')}}">SMART ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{url('admin/post/add')}}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{url('admin/product/add')}}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{url('admin/order/add')}}">Thêm đơn hàng</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('user.edit', Auth::id())}}">Tài khoản</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                @php
                    $module_active = session('module_active');    
                @endphp
                <ul id="sidebar-menu">
                    <li class="nav-link {{$module_active == 'dashboard' ? 'active' : ''}}">
                        @can('dashboard')
                            <a href="{{url('dashboard')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Dashboard
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                    </li>
                    <li class="nav-link {{$module_active == 'page' ? 'active' : ''}}">
                        @can('page-list', Page::class)
                            <a href="{{url('admin/page/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Trang
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        
                        <ul class="sub-menu">
                            @can('page-create', Page::class)
                                <li><a href="{{url('admin/page/add')}}">Thêm mới</a></li>
                            @endcan
                           
                            @can('page-list', Page::class)
                                <li><a href="{{url('admin/page/list')}}">Danh sách</a></li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'post' ? 'active' : ''}}">
                        @can('post-create', Post::class)
                        <a href="{{url('admin/post/list')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bài viết
                        </a>
                        
                        <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        <ul class="sub-menu">
                            @can('post-create')
                                <li><a href="{{url('admin/post/add')}}">Thêm mới bài viết</a></li>
                            @endcan
                        
                            @can('post-list')
                                <li><a href="{{url('admin/post/list')}}">Danh sách bài viết</a></li>
                            @endcan

                            @can('post-category-create')
                                <li><a href="{{url('admin/post/category/add')}}">Thêm mới danh mục</a></li>
                            @endcan
                            
                            @can('post-category-list')
                                <li><a href="{{url('admin/post/category/list')}}">Danh sách danh mục</a></li>
                            @endcan
                           
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'product' ? 'active' : ''}}">
                        @can('product-list')
                            <a href="{{url('admin/product/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Sản phẩm
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        
                     

                        <ul class="sub-menu">
                            @can('product-create')
                                <li><a href="{{url('admin/product/add')}}">Thêm mới sản phẩm</a></li>
                            @endcan
                           
                            @can('product-list')
                                <li><a href="{{url('admin/product/list')}}">Danh sách sản phẩm</a></li>
                            @endcan

                            @can('product-color-create')
                                <li><a href="{{url('admin/product/color/add')}}">Thêm màu sản phẩm</a></li>
                            @endcan
                           
                            @can('product-color-list')
                                <li><a href="{{url('admin/product/color/list')}}">Danh sách màu sản phẩm</a></li>
                            @endcan
                           
                            @can('product-size-create')
                            <li><a href="{{url('admin/product/size/add')}}">Thêm size sản phẩm</a></li>
                            @endcan

                            @can('product-size-list')
                            <li><a href="{{url('admin/product/size/list')}}">Danh sách size sản phẩm</a></li>
                            @endcan

                            @can('product-category-create')
                                <li><a href="{{url('admin/product/category/add')}}">Thêm mới danh mục</a></li>
                            @endcan
                            
                            @can('product-category-list')
                                <li><a href="{{url('admin/product/category/list')}}">Danh sách danh mục</a></li>
                            @endcan
                            
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'order' ? 'active' : ''}}">
                        @can('order-list')
                            <a href="{{url('admin/order/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Bán hàng
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        <ul class="sub-menu">
                            @can('order-list')
                                <li><a href="{{url('admin/order/list')}}">Đơn hàng</a></li>
                            @endcan

                            @can('inventory-statistics')
                                <li><a href="{{url('admin/order/inventory')}}">Thống kê hàng tồn</a></li>
                            @endcan

                            @can('revenue-statistics')
                                <li><a href="{{url('admin/order/revenue')}}">Thống kê doanh thu</a></li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'menu' ? 'active' : ''}}">
                        @can('menu-list', Menu::class)
                            <a href="{{url('admin/menu/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Menu
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        
                        <ul class="sub-menu">
                            @can('menu-create', Menu::class)
                                <li><a href="{{url('admin/menu/add')}}">Thêm mới menu</a></li>
                            @endcan
                           
                            @can('menu-list', Menu::class)
                                <li><a href="{{url('admin/menu/list')}}">Danh sách menu</a></li>
                            @endcan
                            
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'user' ? 'active' : ''}}">
                        @can('user-list', User::class)
                            <a href="{{url('admin/user/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Users
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                       
                        <ul class="sub-menu">
                            @can('user-create')
                                <li><a href="{{url('admin/user/add')}}">Thêm mới user</a></li>
                            @endcan
                           
                            @can('user-list')
                                <li><a href="{{url('admin/user/list')}}">Danh sách user</a></li>
                            @endcan
                            
                            @can('role-create')
                                <li><a href="{{url('admin/role/add')}}">Thêm mới quyền</a></li>
                            @endcan
                           
                            @can('role-list')
                                <li><a href="{{url('admin/role/list')}}">Danh sách quyền</a></li>
                            @endcan
                           
                        </ul>
                    </li>

                    <li class="nav-link {{$module_active == 'slider' ? 'active' : ''}}">
                        @can('slider-list')
                            <a href="{{url('admin/slider/list')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                            Slider
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                        @endcan
                        
                        <ul class="sub-menu">
                            
                            @can('slider-create')
                                <li><a href="{{url('admin/slider/add')}}">Thêm mới slider</a></li>
                            @endcan
                            
                            @can('slider-list')
                                <li><a href="{{url('admin/slider/list')}}">Danh sách slider</a></li>
                            @endcan
                           
                        </ul>
                    </li>
                </ul>
            </div>
            <div id="wp-content">
               @yield('content')
            </div>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/b7vki0m9awaojqkg7o2j28y9xefcavqx4zfszh1cs37jqpjj/tinymce/4/tinymce.min.js" referrerpolicy="origin"></script>
    {{-- <script src="https://cdn.tiny.cloud/1/b7vki0m9awaojqkg7o2j28y9xefcavqx4zfszh1cs37jqpjj/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{asset('js/Chart.min.js')}}"></script>
    <script src="{{asset('js/admin.js')}}"></script>
    <script src="{{asset('js/vendor.js')}}"></script>
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    @yield('script')
</body>

</html>