@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm slider
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('slider.store')}}" class="action-slider" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label for="name">Tên slider</label>
                    <input class="form-control" type="text" name="title" id="name">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="link">Đường dẫn slider</label>
                    <input class="form-control" type="text" name="link" id="link">
                    @error('link')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group thumb-slider">
                    <label for="description">Ảnh slider </label>
                   <input type="file" id="slider-preview" name="file" class="form-control">
                    <img src="" alt="" id="slider-thumbnail" class="thumbnail-admin">
                    @error('file')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                    @if(session('file_error'))
                        <small class="text-danger">{{session('file_error')}}</small>
                    @endif
                </div>

                <div class="form-group" id="slider-sort">
                    <label for="">Thứ tự hiện thị</label>
                    <select name="slider_sort"  class="form-control">
                        @for ($i = 1; $i <= $num_slider; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                    @if(session('error_sort'))
                        <small class="text-danger">{{session('error_sort')}}</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
        </form>
        </div>
    </div>
</div>    
@endsection
