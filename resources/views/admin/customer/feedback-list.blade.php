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
            <h5 class="m-0 ">Danh sách trang</h5>
            <div class="form-search form-inline">
            <form action="{{route('admin.feedback.search')}}" id="search-form-feedback" class="form-search-result">
                <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
            </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('admin.feedback.list','status=active')}}" class="text-primary  @if(request()->status == 'active') {{'active'}} @endif" id="feedback-active" data-active="{{$count[0]}}" data-status="{{request()->status}}">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{route('admin.feedback.list','status=pendding')}}" class="text-primary  @if(request()->status == 'pendding') {{'active'}} @endif" id="feedback-pendding" data-pendding="{{$count[1]}}" data-status="{{request()->status}}">Chờ phê duyệt<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{route('admin.feedback.list','status=trash')}}" class="text-primary  @if(request()->status == 'trash') {{'active'}} @endif" id="feedback-trash" data-trash="{{$count[2]}}" data-status="{{request()->status}}">Thùng rác<span class="text-muted">({{$count[2]}})</span></a>
            </div>
                <form class="form-data-action"  action="{{route('admin.feedback.action')}}" method="POST">
                    @csrf
                    <div class="form-action-feedback form-inline py-3">
                        {{-- @can('action-mau-san-pham') --}}
                            <select class="form-control mr-1 info-action" id="" name="act">
                                <option value="choose">Chọn</option>
                                @foreach ($list_action as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-feedback">
                        {{-- @endcan --}}
                    </div>
                    <div class="table-data-feedback table-data" data-action={{route('admin.feedback.pagination')}}>
                            @include('admin/customer/feedback-pagination')
                    </div>
            
                </form>
        </div>
    </div>
</div> 
@endsection
