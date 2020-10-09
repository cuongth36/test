@extends('layouts.smart')
@section('title','Smart shop - Giỏ hàng')
@section('content')
<div class="main-content">
    <div class="container">
        <div class="breadcrumb-custom">{{Breadcrumbs::render('cart.list') }}</div>
      
        <div class="title-page mr-top">
            <h3>Giỏ hàng</h3>
            
        </div>
    </div>

    <div class="container">
        @if (Cart::total() > 0)
            <div class="cart-destroy">
                <div class="home">
                    <button type="button" class="btn btn-primary"><a href="{{route('homepage')}}">Tiếp tục mua hàng tại đây?</a></button>
                    
                </div>
                <div class="cart-destroy-item">
                    <button type="button" class="btn btn-danger"><a href="{{route('cart.destroy')}}">Xóa giỏ hàng?</a></button>
                </div>
            </div>
        @endif
     
    </div>
    <div class="cart-box-container">
        <div class="container container-ver2">
            <div class="row">
                <div class="col-md-12">
                    @if (Cart::count() > 0)
                        <div class="cart-wrapper">
                            @include('cart/data-content')
                            {{-- <div class="contact-form coupon">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class=" control-label" for="inputfname">Mã giảm giá</label>
                                        <input type="text" class="form-control" id="inputfname" placeholder="Nhập mã giảm giá...">
                                        <button value="Submit" class="btn link-button link-button-v2 hover-white color-red" type="submit">Áp dụng</button>
                                    </div>

                                </form>
                            </div> --}}
                            <div class="cart-total">
                                <p>Tổng: <span class="total-item">{{Cart::total()}}đ</span></p>
                            </div>
                            <div class="pre-checkout">
                                <a class="btn btn-primary link-button hover-white checkout" href="{{route('cart.pre_checkout')}}" title="Thanh toán">Thanh toán</a>
                            </div>

                        </div>
                    @else
                        <div class="cart-empty">
                            <h2>Giỏ hàng <span>(0 sản phẩm)</span></h2>
                            <div class="cart-empty-style">
                                <img src="{{asset("images/mascot@2x.png")}}" alt="">
                                <p class="empty-note">Không có sản phẩm nào trong giỏ hàng</p>
                                <a href="{{route('homepage')}}" class="empty-btn">Tiếp tục mua sắm</a>
                            </div>
                            
                        </div>
                    @endif
                    
                    
                </div>
            </div>
           
           
        </div>
        <!-- End container -->
    </div>
    <!-- End cat-box-container -->
</div>
@endsection
