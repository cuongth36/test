 <!-- Header -->
 <header>
  <header>

    <!-- Top header -->
    <div class="header-topbar">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="st2-info-header">
              <ul class="st2-info-header-list">
                <li class="st2-info-li st2-info-phone">
                  <span>SĐT: 0989769123</span>
                </li>
                <li class="st2-info-li st2-info-email">
                  <span>Email: Smart@gmail.com</span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="st2-info-myacount">
              <ul class="st2-myacount-list">
              <li class="st2-myacount-li st2-myacount">
                <a class="st2-myacount-link" href="{{route('sign-in')}}">
                    @if(!empty(session('login')))
                        Xin chào, {{session('name')}}
                    @else
                        Tài khoản 
                    @endif
                </a>
              </li>
                {{-- <li class="st2-myacount-li st2-wishlist"><a class="st2-myacount-link" href="">Wish List(2)</a>
                </li>
                <li class="st2-myacount-li st2-shopping-cart"><a class="st2-myacount-link" href="">Shopping Cart</a></li>
                <li class="st2-myacount-li st2-checkout"><a class="st2-myacount-link" href="">Checkout</a></li> --}}
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Header bottom -->
    <div class="header-bottom">
      <div class="header-bottom-st2-wrapper">
        <div class="header-bottom-st2-inner">
          <div class="container">
            <div class="row js-compare">
              <div class="col-lg-3 st2-col-lapt-3 col-md-3 col-3">
                <div class="st2-header-logo">
                    <a href="{{route('homepage')}}">
                      <img src="{{asset('images/logo.jpg')}}" alt="Logo">
                    </a>
                </div>
              </div>
              <div class="col-lg-6 hide-reponsive js-dad">
                <!-- Menu -->
               <div class="st2-hamadryad-menu">
                  <nav>
                    <ul class="st2-menu-primary">    
                      @foreach ($menus as $menu)
                        <li class="st2-li-primary">
                        <a class="st2-item-link" href="{{$menu->link}}">{{$menu->title}}</a>
                        @if($menu->multiChildMenu->count())
                        <div class="st2-hamadryad-megamenu st1-hamadryad-megamenu megamenu-home js-dropmenuv1 megamenu-bg-active1">
                          <section class="st2-hamadryad-megamenu-modal home">
                            <div class="container-fluid">
                              <div class="st2-hamadryad-submenu-wrapper">
                                <div class="row">
                                 
                                      @foreach ($menu->multiChildMenu as $item)
                                        <div class="col-md-4">
                                          <div class="st2-submenu-list-item">
                                            <h2 class="st2-submenu-item-title">
                                            <a href="{{$item->link}}">{{$item->title}}</a>
                                            </h2>
                                            @include('pages/menu/child-menu', ['menu' => $item])
                                          </div>
                                        </div>
                                      @endforeach
                                
                                </div>
                              </div>
                            </div>
                          </section>
                        </div>
                        @endif
                          <!-- Drop menu -->
                            {{-- @include('pages/menu/child-menu', ['menu' => $menu]) --}}
                        </li>
                      @endforeach                                                         
                    </ul>
                  </nav>
                </div>
              </div>
              <div class="col-lg-3 st2-col-lapt-9 col-md-9 col-9">
                <div class="st2-control-right">
                  <div class="st2-control">
                    <!-- search mobie -->
                    <div class="search-mobie" data-toggle="collapse" data-target="#showSearch" aria-expanded="true">
                      <span class="lnr lnr-magnifier"></span>
                    </div> 
                    <!-- search -->
                    <div class="st2-search-box st2-control-block hide-reponsive">
                      <div class="st2-search-block">
                      <form method="post" class="st2-search-form" action="{{route('search.info')}}">
                          @csrf
                          <div class="at2-search-fields">
                            <div class="st2-search-input">
                              <input type="text" class="st2-search-field" placeholder="Tìm kiếm sản phẩm" value="" name="s">
                              <button type="submit" class="st2-search-submit">
                                <span class="lnr lnr-magnifier"></span>
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- cart -->
                    <div class="st2-cart-block st2-control-block js-click-cart">
                      <div class="st2-cart-icon">
                        <span class="icon-ecommerce-basket"></span>
                        <span class="st2-cart-number">{{Cart::count()}}</span>
                      </div>
                    </div>
                    <!-- mega menu -->
                    <div class="st2-megamenu-block st2-control-block js-click-megamenu">
                      <div class="st2-megamenu-icon">
                        <span class="lnr lnr-menu"></span>
                      </div>
                    </div>
  
                  </div>
                </div>
              </div>
              <!-- Form search -->
              <form method="post" class="search-form collapse" action="{{route('search.info')}}" id="showSearch">
                @csrf
                <div class="search-fields">
                  <div class="search-input">
                    <span class="reset-instant-search-wrap"></span>
                    <input type="search" class="search-field" placeholder="Instant search ..." value="" name="s">
                    <button type="submit" class="search-submit">
                      <span class="lnr lnr-magnifier"></span>
                    </button>
                  </div>
                </div>
              </form>
              <!-- End Form search -->
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Mini cart -->
    <div class="hamadryad-minicart">
      <div class="hamadryad-minicart-content">
        <h3 class="hamadryad-minicart-title">Giỏ hàng</h3>
        <span class="hamadryad-minicart-number"><span class="number-product-item">{{Cart::count()}}</span> sản phẩm</span>
        <div class="hamadryad-minicart-close">
          <i class="lnr lnr-cross"></i>
        </div>
        @if (Cart::count() ==0)
          <div class="subtotal">
            <p>Không có hàng trong giỏ</p>
          </div>
        @elseif(Cart::count() >0)
          <div class="hamadryad-minicart-list-item">
            <div class="list-item">
              <ol class="hamadryad-minicart-items">
                @if (!empty(Cart::content()))
                  @foreach (Cart::content() as $item)
                    <li class="product-cart">
                      <a href="{{route('product.detail', [$item->options['slug'], '.html'])}}" class="product-cart-thumb"><img src="{{url($item->options->thumbnail)}}" alt=""></a>
                      <div class="product-detail">
                        <div class="product-name"><a href="{{route('product.detail', [$item->options['slug'], '.html'])}}">{{$item->name}}</a></div>
                        <div class="product-detail-info">
                          <div class="product-quality">Số lượng : <span class="data-qty" data-id="{{$item->id}}">{{$item->qty}}</span></div>
                          <div class="product-cost"><span style="color:#222222">Giá tiền:</span> <span class="data-total-product" data-id="{{$item->id}}">{{number_format($item->total, 0 , ' ', '.')}}đ</span></div>
                        </div>
                      </div>
                      {{-- <div class="product-remove">
                        <a href=""><i class="lnr lnr-cross"></i></a>
                      </div> --}}
                    </li>
                  @endforeach
                @endif
              </ol>
            </div>
          </div>
            <div class="subtotal">
              <span class="total-title">Tổng:</span>
              <span class="total-price">{{Cart::total()}}đ</span>
            </div>
      
            <div class="hamadryad-minicart-action">
                  <div class="btn-action-minicart viewcart">
                    <a href="{{route('cart.list')}}">Giỏ hàng</a>
                  </div>
                  <div class="btn-action-minicart checkout">
                    <a href="{{route('cart.pre_checkout')}}">Thanh toán</a>
                  </div>
            </div>
        @endif
        
       
      </div>
    </div>
    <div class="minicart-bg-overlay" style="display: none;"></div>
  
    <!-- Menu mobie -->
    <div class="box-mobile-menu">
      <span class="box-title">Menu</span>
      <a href="#" class="close-menu" id="pull-closemenu">
          <i class="lnr lnr-cross"></i>
      </a>
      <div class="menu-clone">
        <ul class="main-menu">
          @foreach ($menus as $menu)
          <li class="menu-item menu-item-has-children">
            <a href="{{$menu->link}}">{{$menu->title}}</a>
            @if($menu->multiChildMenu->count())
            <span class="toggle-submenu"></span>
            <div class="submenu">
              <div class="col-12 col-md-12 custom-col">
                <div class="col-inner">
                  @foreach ($menu->multiChildMenu as $item)
                  <div class="col-wrapper-item">
                    <h2 class="widget-title"> <a href="{{$item->link}}">{{$item->title}}</a></h2>
                      @include('pages/menu/child-menu-mobile', ['menu' => $item])
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            @endif
          </li>
          @endforeach 
          <li class="menu-item menu-item-has-children">
            <a class="st2-myacount-link" href="{{route('sign-in')}}">
                @if(!empty(session('login')))
                    Xin chào, {{session('name')}}
                @else
                    Tài khoản 
                @endif
            </a>
          </li>
        </ul>
        
      </div>
    </div>
    <div class="menu-overlay"></div>
  
  </header>
</header>