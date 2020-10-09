@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm trang mới
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('page.store')}}">
            @csrf
                <div class="form-group custom-title">
                    <label for="title">Tiêu đề trang</label>
                    <input class="form-control change-title" type="text" name="title" id="title">
                </div>

                <div class="form-group custom-slug">
                    <label for="name">Slug</label>
                    <input class="form-control change-slug" type="text" name="slug" id="slug">
                    @error('slug')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content">Nội dung</label>
                    <textarea name="content" class="content" id="content" cols="30" rows="10"></textarea>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="pendding" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Chờ duyệt
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="approve">
                        <label class="form-check-label" for="exampleRadios2">
                          Công khai
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection
