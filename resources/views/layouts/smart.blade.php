<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font family -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:400,500,700&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Libary -->
    <link rel="stylesheet" href="{{asset('css/carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/carousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/vendors/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/vendors/slick/slick-theme.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/css/animate.min.css')}} ">
    <link rel="stylesheet" href="{{asset('frontend/vendors/rangeslider/css/ion.rangeSlider.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('frontend/vendors/rangeslider/css/theme.scss.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/vendors/rangeslider/css/layout.min.css')}}">

    <!-- Font -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('frontend/fonts/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/fonts/linearicons/style.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/fonts/linea/styles.css')}}">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}">
     <link rel="stylesheet" href="{{asset('frontend/scss/style.css')}}">

     <title>@yield('title', 'Smart shop')</title>
  </head>
  <body>
   @include('header/header')

    <!-- Slide -->
    <div class="slide-page1">
        @yield('slider')
    </div>

    <!-- Main content -->
    <div class="main-content">
      @yield('content')
    </div>

    <!-- Footer -->
    <footer>
      <div class="footer-wrapper">
        <div class="footer footer-bg">
          <div class="footer-content footer-content-pd">
            <div class="footer-item">
                <div class="container">
                  <div class="row">
                    <div class="col-12 col-md-3 custom-col-pt col-xl-3 t-center-laptop">
                      <h2 class="f-content-title f-content-title-color-st1">CỬA HÀNG SMART MOBILE
                        <span class="icon-plus"><i class="fa fa-plus-square"></i></span>
                      </h2>
                      <ul class="f-content-list-item">
                        <li class="f-content-items">Cơ sở 1: 39 Phú Đô, Nam Từ Liêm, Hà Nội</li>
                        <li class="f-content-items">Cơ sở 2: 20 Đông Tiến, Đông Sơn, Thanh Hóa</li>
                      </ul>
                    </div>
                    <div class="col-12 col-md-3 custom-col-pt col-xl-3 t-center-laptop">
                      <h2 class="f-content-title f-content-title-color-st1">VỀ CHÚNG TÔI
                        <span class="icon-plus"><i class="fa fa-plus-square"></i></span>
                      </h2>
                      <ul class="f-content-list-item">
                      <li class="f-content-items"><a class="fc-item fc-item-color-st1" href="https://cuong9x.herokuapp.com/gioi-thieu.html">Giới thiệu cửa hàng</a></li>
                        <li class="f-content-items"><a class="fc-item fc-item-color-st1" href="https://cuong9x.herokuapp.com/lien-he.html">Liên hệ với chúng tôi</a></li>
                      </ul>
                    </div>
                    <div class="col-12 col-md-3 custom-col-pt col-xl-3 t-center-laptop">
                      <h2 class="f-content-title f-content-title-color-st1">HỖ TRỢ KHÁCH HÀNG
                        <span class="icon-plus"><i class="fa fa-plus-square"></i></span>
                      </h2>
                      <ul class="f-content-list-item">
                        <li class="f-content-items"><a class="fc-item fc-item-color-st1" href="https://cuong9x.herokuapp.com/chinh-sach-mua-hang.html">Chính sách mua hàng</a></li>
                        <li class="f-content-items"><a class="fc-item fc-item-color-st1" href="https://cuong9x.herokuapp.com/chinh-sach-bao-hang.html">Chính sánh bảo hành</a></li>
                      </ul>
                    </div>
                    <div class="col-12 col-md-3 custom-col-pt col-xl-3 t-center-laptop">
                      <h2 class="f-content-title f-content-title-color-st1">FANPAGE
                        <span class="icon-plus"><i class="fa fa-plus-square"></i></span>
                      </h2>
                     
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="footer-info">
            <div class="container">
              <div class="row">
                <div class="col-md-6 cl-md-ipad-12 col-lg-6 col-xl-6 order-mobile-cp">
                  <div class="info-copyright">
                    <span>Copyright © 2020 by Cuong Design All Rights Reserved</span>
                  </div>
                </div>
                
                <div class="col-md-6 cl-md-ipad-12 col-lg-3 col-xl-6 order-mobile-sc">
                  <div class="social-inner tx-center">
                    <span class="social-text">Theo dõi:</span>
                    <div class="socials">
                      <a href="" class="icon-social">
                        <i class="ti-facebook"></i>
                      </a>
                      <a href="" class="icon-social">
                        <i class="ti-twitter-alt"></i>
                      </a>
                      <a href="" class="icon-social">
                        <i class="ti-pinterest"></i>
                      </a>
                      <a href="" class="icon-social">
                        <i class="ti-youtube"></i>
                      </a>
                      <a href="" class="icon-social">
                        <i class="ti-instagram"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <div id="btn-top"><img src="{{asset('images/icon-to-top.png')}}" alt=""/></div>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{asset('js/app.js') }}"></script>
    {{-- <script src="{{asset('frontend/vendors/owlcarousel/owl.carouselv2.2.min.js')}}"></script> --}}
    <script src="{{asset('js/carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('frontend/vendors/slick/slick.min.js')}}"></script>
    <script src="{{asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/vendors/rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('frontend/js/custom.js')}}"></script>
    <script src="{{asset('js/elevatezoom-master/jquery.elevatezoom.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>

    
  </body>
</html>