@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm menu
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('menu.store')}}" class="action-menu">
            @csrf
                <div class="form-group custom-title">
                    <label for="name">Tên menu</label>
                    <input class="form-control change-title" type="text" name="title" id="name">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group custom-slug">
                    <label for="slug">Slug đường dẫn</label>
                    <input class="form-control change-slug" type="text" name="slug" id="slug">
                    @error('slug')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="link">Đường dẫn menu</label>
                    <input class="form-control" type="text" name="link" id="link">
                    @error('link')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Menu cha</label>
                    <select class="form-control" id="menu-parent" name="category_parent">
                      <option value="0">Chọn danh mục</option>
                      @foreach ($resutl as $value)
                        <option value="{{$value->id}}">{{str_repeat('- ', $value->level) . $value->title }}</option>
                      @endforeach
                    </select>
                </div>

                <div class="form-group" id="menu-sort">
                    <label for="">Thứ tự hiện thị</label>
                    <select name="menu_sort"  class="form-control">
                        @for ($i = 1; $i <= $num_menu; $i++)
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
