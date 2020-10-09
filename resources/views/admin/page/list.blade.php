@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="message alert alert-success"></div>
        @if(session('status'))
            <div class="alert alert-success">{{session('status')}}</div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách trang</h5>
            <div class="form-search form-inline">
            <form action="{{route('page.search')}}" id="search-form-page" class="form-search-result">
                <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
            </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('page.list','status=active')}}" class="text-primary  @if(request()->status == 'active') {{'active'}} @endif" id="page-active" data-active="{{$count[0]}}" data-status="{{request()->status}}">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{route('page.list','status=pendding')}}" class="text-primary  @if(request()->status == 'pendding') {{'active'}} @endif" id="page-pendding" data-pendding="{{$count[1]}}" data-status="{{request()->status}}">Chờ phê duyệt<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{route('page.list','status=trash')}}" class="text-primary  @if(request()->status == 'trash') {{'active'}} @endif" id="page-trash" data-trash="{{$count[2]}}" data-status="{{request()->status}}">Thùng rác<span class="text-muted">({{$count[2]}})</span></a>
            </div>
                <form class="form-data-action"  action="{{route('page.action')}}" method="POST">
                    @csrf
                   
                        <div class="form-action-page form-inline py-3">
                            @can('action-trang')
                                <select class="form-control mr-1 info-action" id="" name="act">
                                    <option value="choose">Chọn</option>
                                    @foreach ($list_action as $key=>$item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-page">
                            @endcan
                        </div>
                    
                    <div class="table-data-page table-data" data-action={{route('page.pagination')}}>
                            @include('admin/page/pagination')
                    </div>
            
                </form>
        </div>
    </div>
</div> 
@endsection
