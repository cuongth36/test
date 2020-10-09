@extends('layouts.smart')
@section('title','Smart shop - Thanh toán')
@section('content')
<div class="pushmenu pushmenu-left cart-box-container">
    <div class="main-content mr-top">
        <div class="container">
            <div class="breadcrumb-custom">{{Breadcrumbs::render('cart.checkout') }}</div>
            <div class="title-page">
                <h3>Thanh toán</h3>
            </div>
        </div>
        <div class="cart-box-container check-out">
            <div class="container container-ver2">
                @if (Cart::total() >0)
                <form class="form-horizontal" action="{{route('cart.bill')}}" method="POST">
                    @csrf
                <div class="row"> 
                    <div class="col-md-8">
                        <h3 class="title-v3">Thông tin khách hàng</h3>
                        
                           
                            <div class="form-group">
                                <label for="inputfname" class=" control-label">Họ tên*</label>                            
                            <input type="text" name="fullname" id="inputfname" class="form-control" value="{{$customer->name}}">  
                                @error('fullname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                          
                            <div class="form-group">
                                <label for="inputemail" class=" control-label">Email*</label>                            
                                <input type="email" name="email" id="inputemail" class="form-control" value="{{$customer->email}}">  
                                @error('email')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inputphone" class=" control-label">Số điện thoại*</label>                            
                                <input type="text" name="phone" id="inputphone" class="form-control" value="{{$customer->phone}}">  
                                @error('phone')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="address" class="control-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" id="address" value="{{$customer->address}}">
                                @error('address')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="note" class="control-label">Ghi chú</label>
                                <textarea name="note" id="note" cols="30" rows="8" class="form-control"></textarea>
                            </div>
                        
                    </div>
                    <!-- End col-md-8 -->
                    <div class="col-md-4">
                       
                        <div class="text-price">
                            <h3>Hóa đơn của bạn</h3>
                            <ul>
                                <li><span class="text bold">Sản phẩm</span><span class="number bold">Tổng</span></li>
                                @foreach (Cart::content() as $item)
                                    <li><span class="text bold text-cap">{{$item->name}}<br>Số lượng : {{$item->qty}}</span></span><span class="number">{{number_format($item->total, 0 , '', '.')}}đ</span></li>
                                @endforeach
                                <li><span class="text">Vận chuyển</span>
                                    <div class="payment shipping-order">
                                        <div class="form-group  d-flex">
                                            <input type="radio" name="shipping" value="free" id="radio2" checked class="shipping-method">
                                            <label for="radio2">Giao hàng miễn phí (3-6 ngày)</label>
                                        </div>

                                        <div class="form-group  d-flex">
                                            <input type="radio" name="shipping" value="delivery" id="radio3" class="shipping-method ">
                                            <label for="radio3">Giao hàng nhanh trong ngày 30k</label>
                                        </div>
                                    </div>
                                </li>
                                <li><span class="text">Phí vận chuyển</span><span class="number" id="total-ship">0đ</span></li>
                                    
                                <li><span class="text">Tổng tiền</span><span class="number total-order" data-total={{Cart::total()}}>{{Cart::total()}}đ</span></li>
                            </ul>
                            <div class="payment-order">
                            <ul class="tabs">
                                <li>
                                    <i class="icon"></i>
                                    <h4>Phương thức thanh toán</h4>
                                </li>
                                <li>
                                    <div class="form-group d-flex">
                                        <input type="radio" id="payment-home"  name="payment-method" value="payment-home" checked>
                                        <label for="payment-home">Thanh toán khi nhận hàng</label>
                                    </div>
                                    
                                </li>
                                <li>
                                    <div class="form-group d-flex">
                                        <input type="radio" id="direct-payment"  name="payment-method" value="direct-payment">
                                        <label for="direct-payment">Thanh toán qua thẻ</label>
                                    </div>
                                    
                                </li>
                               
                            </ul>
                            
                            </div>
                            <input type="submit" class="process-order btn-primary" value="Tiến hành đặt hàng">
                           
                        </div>
                       
                        
                    </div>
               
                </div>
            </form>
            @endif
            </div>
            <!-- End container -->
        </div>
        <!-- End cat-box-container -->
    </div>
   
    </div>
<!-- End wrappage -->
@endsection