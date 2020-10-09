@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
           Cập nhật người dùng
        </div>
        <div class="card-body">
            <form action="{{route('user.update', $user->id)}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}">
                    @error('name')
                        <small class="text-danger">{{$message}}</small>    
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" readonly>
                    @error('email')
                        <small class="text-danger">{{$message}}</small>    
                    @enderror
                </div>
                @if ($user->id == Auth::id())
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password" >
                        @error('password')
                            <small class="text-danger">{{$message}}</small>    
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                        @if (session('error'))
                            <small class="text-danger">{{session('error')}}</small>
                        @endif
                    </div>
                @endif
              
                <div class="form-group">
                    @php
                         $arr = [];
                         $user_admin = Auth::user();
                         foreach ($user_admin->roles as $v) {
                            $arr[] =$v->key_code;
                         }
                    @endphp
                    @if (in_array('sua-user',$arr))
                        <label for="">Nhóm quyền</label>
                        <hr>
                        <div class="check-multi">
                            <input type="checkbox" name="check-all-role" id="checkall">
                            <label for="checkall">Chọn tất cả</label>
                        </div>
                
                        <div class="user-role">
                            @foreach ($roles as $item)
                                    <div class="role-item">
                                        <input type="checkbox" name="role_list[]" value="{{$item->id}}" {{in_array($item->id, $data_select) ? 'checked' : ''}}>
                                        <label for="">{{$item->name}}</label>
                                    </div>
                                
                            @endforeach
                        </div>
                    @endif
                    
                </div>

                <button type="submit" name="btn-add" value="Thêm mới" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>      
@endsection