@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
           Cập nhật phản hồi
        </div>
        <div class="card-body">
            <form action="{{route('admin.feedback.update', $contact->id)}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$contact->name}}" readonly>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{$contact->email}}" readonly>
                </div>

                <div class="form-group">
                    <label for="password">Tiêu đề</label>
                    <input class="form-control" type="text" name="password" id="password" value="{{$contact->title}}" readonly>
                </div>

                <div class="form-group">
                    <label for="password">Nội dung</label>
                    <input class="form-control" type="text" name="password" id="password" value="{{$contact->content}}" readonly>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" id="" class="form-control">
                        <option value="0" {{$contact->status == '0' ? 'selected' : ''}}>Đang xử lý</option>
                        <option value="1" {{$contact->status == '1' ? 'selected' : ''}}>Xử lý xong</option>
                    </select>
                </div>

                <button type="submit" name="btn-add" value="Thêm mới" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>      
@endsection