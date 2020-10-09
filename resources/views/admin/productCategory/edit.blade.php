@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhập danh mục
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('category_product.update', $categories->id)}}" enctype="multipart/form-data">
            @csrf
                <div class="form-group custom-title">
                    <label for="name">Tên danh mục</label>
                    <input class="form-control change-title" type="text" name="title" id="name" value="{{$categories->title}}">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group custom-slug">
                    <label for="slug">Tên slug</label>
                    <input class="form-control change-slug" type="text" name="slug" id="slug" value="{{$categories->slug}}">
                    @error('slug')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="link">Link danh mục</label>
                    <input class="form-control" type="text" name="link" id="link" value="{{$categories->link}}">
                    @error('link')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group  thumb-category">
                    <label for="description">Ảnh đại diện </label>
                   <input type="file" id="thumbnail-category-preview" name="file" class="form-control">
                   @if (!empty($categories->thumbnail))
                     <img src="{{url($categories->thumbnail)}}" alt="{{$categories->title}}"  class="thumbnail feature-product thumbnail-exits-cate">
                     <img src="" alt="" class="thumbnail feature-product thumbnail-category">
                    @else
                    <img src="" alt="" class="thumbnail feature-product thumbnail-category">
                   @endif
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
                      <option value="">Chọn danh mục</option>
                      @foreach ($list_categories as $key=>$value)
                         <option value="{{$value->id}}" @if($value->id == $value->parent_id){{'selected'}}@endif>{{str_repeat('- ', $value->level) . $value->title }}</option>
                      @endforeach
                    </select>
                    @if (session('error_parent'))
                        <small class="text-danger">{{session('error_parent')}}</small>
                    @endif
                </div>


                <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
        </div>
    </div>
</div>    
@endsection
