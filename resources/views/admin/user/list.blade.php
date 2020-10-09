@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="message alert alert-success"></div>
        <div class="form-message"></div>
        @if (session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
        @endif

        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách thành viên</h5>
            <div class="form-search form-inline">
            <form action="{{route('user.search')}}" id="form-search-user" class="form-search-result">
                <input type="text" class="form-control keyword" id="keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
            </form>
            </div>
        </div>
        <div class="card-body">
            
            <div class="analytic">
             <a href="{{route('user.list','status=active')}}" class="text-primary user-active @if(request()->input('status') == 'active') {{'active'}} @endif" data-active={{$count[0]}}>Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
            <a href="{{route('user.list','status=trash')}}" class="text-primary user-trash @if(request()->input('status') == 'trash') {{'active'}} @endif" data-trash={{$count[1]}}>Thùng rác<span class="text-muted">({{$count[1]}})</span></a>
            </div>
            <form class="form-data-action" action="{{route('user.action')}}" method="POST">
                @csrf
               
                    <div class="form-action form-inline py-3">
                        @can('action-user')
                        <select class="form-control mr-1 info-action" name="act" id="">
                            <option value="choose">Chọn</option>
                            @foreach ($list_action as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-user">
                        @endcan
                    </div>
               
                <div class="table-data table-data-user" data-action={{route('user.pagination')}}>
                    @include('admin/user/pagination')
                    
                </div>
            </form>
        </div>
    </div>
</div>    
@endsection