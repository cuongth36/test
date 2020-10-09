@extends('layouts.smart')
@section('title','Smart shop - Đăng ký tài khoản')
@section('content')

<div class="container">
  <div class="row">
      <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center">
          <div class="row">
              <div class="col-lg-6 col-md-8 mx-auto">

                  <!-- form card login -->
                  <div class="card rounded shadow shadow-sm mr-bottom">
                      <div class="card-body">
                      <form class="form" action="{{route('customer.store')}}" role="form" autocomplete="off" id="formRegister" novalidate="" method="POST">
                              @csrf
                              <h2>Đăng ký</h2>
                              <p>Vui lòng điền vào biểu mẫu này để tạo một tài khoản.</p>
                              <hr>
                              @if(session('status'))
                                  <div class="alert alert-success">{{session('status')}}</div>
                              @endif
                              <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="fullname" name="fullname">
                                @error('fullname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                              </div>

                              <div class="form-group">
                                  <label for="uname1">Email</label>
                                  <input type="email" class="form-control form-control-lg rounded-0" name="email" id="uname1" required="">
                                  <div class="invalid-feedback">Email không được bỏ trống.</div>
                                  @error('email')
                                    <small class="text-danger">{{$message}}</small>
                                  @enderror
                              </div>

                              <div class="form-group">
                                  <label>Mật khẩu</label>
                                  <input type="password" class="form-control form-control-lg rounded-0" id="pwd1" required="" name="password" autocomplete="new-password">
                                  <div class="invalid-feedback">Mật khẩu không được bỏ trống.</div>
                              </div>
                              
                            
                              <div class="form-group">
                                  <label for="psw-repeat">Xác nhận mật khẩu</label>
                                  <input type="password" class="change-bg form-control form-control-lg rounded-0"  id="psw-repeat" name="password_confirmation">
                                  @error('password')
                                    <small class="text-danger">{{$message}}</small>
                                  @enderror
                              </div>

                              <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="address" name="address">
                                @error('address')
                                 <small class="text-danger">{{$message}}</small>
                                @enderror
                              </div>
                            
                              <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="phone" name="phone">
                                @error('phone')
                                  <small class="text-danger">{{$message}}</small>
                                @enderror
                              </div>

                              {{-- <div class="form-group">
                                <label class="form-check-label">
                                  <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px" value="0">  Tôi đồng ý với điều khoản <a href="#" style="color:dodgerblue">Terms & Privacy</a>.
                                </label>
                              </div> --}}

                             
                              <button type="submit" class="btn btn-success btn-lg float-right" id="btnLogin">Đăng ký</button>
                              <div class="redirect-login">
                                <p>Bạn đã có tài khoản <a class="color-link" href="{{route('customer.login')}}">đăng nhập</a> tại đây.</p>
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
