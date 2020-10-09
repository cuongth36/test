@extends('layouts.smart')
@section('title','Smart shop - Quên mật khẩu')
@section('content')
<div class="container">
    <div class="forgot-password-wrapper">
        <form action="{{route('customer.check-forgot')}}" method="POST">
            @csrf
            <div class="card forgot-form">
                <div class="card-body">
                    <h3 class="card-title text-center">Khôi phục mật khẩu</h3>
                    @if (session('email_success'))
                    <div class="alert alert-success">
                            {{session('email_success')}}
                    </div>
                    @endif

                    @if (session('email_fail'))
                        <div class="alert alert-danger">
                            {{session('email_fail')}}
                        </div>
                    @endif
                   
                    <div class="card-text">
                        <form>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nhập địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết để đặt lại mật khẩu của bạn.</label>
                                <input type="email" class="form-control form-control-sm" placeholder="Nhập email" name="email_forgot">
                            </div>
            
                            <button type="submit" class="btn btn-primary btn-block">Gửi</button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection