@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="message alert alert-success"></div>
        <div class="form-message"></div>
        @if(session('status'))
            <div class="alert alert-success">{{session('status')}}</div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh mục bài viết</h5>
            <div class="form-search form-inline">
                <form action="{{route('post.search')}}" id="search-form-post" class="form-search-result">
                    <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('post.list','status=active')}}" class="text-primary @if(request()->status == 'active'){{'active'}} @endif" id="post-active" data-active="{{$count[0]}}" data-status="active">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{route('post.list','status=pendding')}}" class="text-primary @if(request()->status == 'pendding'){{'active'}}@endif" id="post-pendding" data-pendding="{{$count[1]}}" data-status="pendding">Chờ phê duyệt<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{route('post.list','status=trash')}}" class="text-primary @if(request()->status == 'trash'){{'active'}}@endif" id="post-trash" data-trash="{{$count[2]}}" data-status="trash">Thùng rác<span class="text-muted">({{$count[2]}})</span></a>
            </div>
            <form class="form-data-action"  action="{{route('post.action')}}" method="POST">
                @csrf
                
                    <div class="form-action-post form-action form-inline py-3">
                        @can('action-bai-viet')
                            <select class="form-control info-action mr-1" id="" name="act">
                                <option value="choose">Chọn</option>
                                @foreach ($list_action as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-post">
                        @endcan
                    </div>
                
                <div class="table-data table-data-post" data-action="{{route('post.pagination')}}">
                        @include('admin/post/pagination')
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
