@extends('layouts.smart')
@section('title','Smart shop - Đăng nhập')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">

                    <!-- form card login -->
                    <div class="card rounded shadow shadow-sm mr-bottom">
                        <div class="card-header">
                            <h3 class="mb-0">Đăng nhập</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{route('customer.checklogin')}}" role="form" autocomplete="off" id="formLogin" novalidate="" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="uname1">Email</label>
                                    <input type="email" class="form-control form-control-lg rounded-0" name="email" id="uname1" required="">
                                    <div class="invalid-feedback">Email không được bỏ trống.</div>
                                </div>
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input type="password" class="form-control form-control-lg rounded-0" id="pwd1" required="" name="password" autocomplete="new-password">
                                    <div class="invalid-feedback">Mật khẩu không được bỏ trống.</div>
                                </div>
                                
                                <div class="message">
                                    @if(session('error'))
                                         <div class="alert alert-danger">{{session('error')}}</div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-success btn-lg float-right" id="btnLogin">Đăng nhập</button>
                                <div class="create-account">
                                    <a href="{{route('customer.register')}}">Tạo tài khoản mới</a>
                                    <a href="{{route('customer.forgot')}}">Quên mật khẩu?</a>
                                </div>
                            </form>
                        </div>
                        <!--/card-block-->
                    </div>
                    <!-- /form card login -->

                </div>


            </div>
            <!--/row-->

        </div>
        <!--/col-->
    </div>
    <!--/row-->
</div>
<!--/container-->
@endsection