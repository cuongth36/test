@extends('layouts.smart')
@section('title','Smart shop - Xác nhận mật khẩu')
@section('content')
<div class="forgot-password-wrapper">
    <form action="{{route('customer.confirm', "confirm=$confirm_pass")}}" method="POST">
        @csrf
        <div class="card forgot-form">
            <div class="card-body">
                <h3 class="card-title text-center">Xác nhận khôi phục mật khẩu</h3>
                @if (session('confirm_success'))
                <div class="alert alert-success">
                        {{session('confirm_success')}}
                </div>
                @endif
               
                <div class="card-text">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Mật khẩu mới</label>
                            <input type="password" class="form-control form-control-sm" placeholder="Nhập mật khẩu" name="password">
                            @error('password')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control form-control-sm" placeholder="Xác nhận mật khẩu" name="password_confirmation">
                        </div>
        
                        <button type="submit" class="btn btn-primary btn-block">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection