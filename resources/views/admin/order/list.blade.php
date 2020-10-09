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
            <h5 class="m-0 ">Danh sách đơn hàng</h5>
            <div class="form-search form-inline">
                <form action="{{route('order.search')}}" id="search-form-color" class="form-search-result">
                    @csrf
                    <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('order.list','status=active')}}" class="text-primary  @if(request()->status == 'active') {{'active'}} @endif" id="order-active" data-active="{{$count[0]}}" data-status="{{request()->status}}">Hoàn thành<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{route('order.list','status=pendding')}}" class="text-primary  @if(request()->status == 'pendding') {{'active'}} @endif" id="order-pendding" data-pendding="{{$count[1]}}" data-status="{{request()->status}}">Đang xử lý<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{route('order.list','status=trash')}}" class="text-primary  @if(request()->status == 'trash') {{'active'}} @endif" id="order-trash" data-trash="{{$count[2]}}" data-status="{{request()->status}}">Hủy<span class="text-muted">({{$count[2]}})</span></a>
            </div>
            <form action="{{route('order.action')}}" method="POST" class="form-data-action">
                @csrf
               
                    <div class="form-action-order form-inline py-3">
                        @if (request()->status != 'active')
                            @can('action-don-hang')
                            <select class="form-control mr-1 info-action" id="" name="act">
                                <option value="choose">Chọn</option>
                                @foreach ($list_action as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-order">
                            @endcan
                        @endif
                    </div>
                   
                <div class="table-data-order table-data" data-action={{route('order.pagination')}}>
                    @include('admin/order/pagination')
                </div>
            </form>
        </div>
    </div>
    
</div>
@endsection