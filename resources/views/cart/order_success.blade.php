@extends('layouts.smart')
@section('title','Smart shop - Đặt hàng thành công')
@section('content')
<div class="container">
    <div class="order-succes">
   
        <div class="order-success-icon">
            <i class="fa fa-check-circle"></i>
        </div>
        <h2 class="title">Cảm ơn quý khách đã mua hàng tại Smart</h2>
        <p class="sub-title">Chúng tôi đã gữi thông tin đơn hàng vào email của quý khách, vui lòng kiểm tra email của quý khách hoặc đăng nhập để kiểm tra đơn hàng</p>
        <p class="order-info">Đơn hàng: {{$order_info->order_code}} - Đang xử lý</p>
        <p class="order-date">Ngày đặt hàng: {{$order_info->created_at}}</p>
       
    </div>
</div>


@endsection