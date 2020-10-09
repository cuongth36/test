@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm danh mục
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('category_product.store')}}" enctype="multipart/form-data">
            @csrf
                <div class="form-group custom-title">
                    <label for="name">Tên danh mục</label>
                    <input class="form-control change-title" type="text" name="title" id="name">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group custom-slug">
                    <label for="slug">Slug</label>
                    <input class="form-control change-slug" type="text" name="slug" id="slug">
                    @error('slug')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="link">Link danh mục</label>
                    <input class="form-control" type="text" name="link" id="link">
                    @error('link')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group thumb-category">
                    <label for="description">Ảnh đại diện </label>
                   <input type="file" id="thumbnail-category-preview" name="file" class="form-control">
                    <img src="" alt=""  class="thumbnail feature-product thumbnail-category">
                    @error('file')
                        <small class="text-danger">{{$message}}</small>
                    @enderror

                    @if(session('file_error'))
                        <small class="text-danger">{{session('file_error')}}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Danh mục cha</label>
                    <select class="form-control" id="" name="category_parent">
                      <option value="0">Chọn danh mục</option>
                      @foreach ($resutl as $value)
                        <option value="{{$value->id}}">{{str_repeat('- ', $value->level) . $value->title }}</option>
                      @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
        </form>
        </div>
    </div>
</div>    
@endsection
