@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
           Cập nhật danh mục
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('category.update', $categories->id)}}">
            @csrf
                <div class="form-group custom-title">
                    <label for="name">Tên danh mục</label>
                    <input class="form-control change-title" type="text" name="title" id="name" value="{{$categories->title}}">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group custom-slug">
                    <label for="slug">Slug</label>
                    <input class="form-control change-slug" type="text" name="slug" id="slug" value="{{$categories->slug}}">
                    @error('slug')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
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
