@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm màu mới
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('color.store')}}">
            @csrf
                <div class="form-group">
                    <label for="title">Tên màu</label>
                    <input class="form-control" type="text" name="title" id="title">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="color">Mã màu</label>
                    <input type="color" id="head" name="color"
                    value="#e66465">
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="0" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Chờ duyệt
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="1">
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
