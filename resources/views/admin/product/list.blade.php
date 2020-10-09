@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="form-message"></div>
        <div class="message alert alert-success"></div>
        @if(session('status'))
            <div class="alert alert-success">{{session('status')}}</div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách sản phẩm</h5>
            <div class="form-search form-inline">
                <form action="{{route('product.search')}}" id="search-form-product" class="form-search-result">
                    <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('product.list','status=active')}}" class="text-primary @if(request()->status == 'active'){{'active'}} @endif" id="product-active" data-active="{{$count[0]}}" data-status="active">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{route('product.list','status=pendding')}}" class="text-primary @if(request()->status == 'pendding'){{'active'}}@endif" id="product-pendding" data-pendding="{{$count[1]}}" data-status="pendding">Chờ phê duyệt<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{route('product.list','status=trash')}}" class="text-primary @if(request()->status == 'trash'){{'active'}}@endif" id="product-trash" data-trash="{{$count[2]}}" data-status="trash">Thùng rác<span class="text-muted">({{$count[2]}})</span></a>
            </div>
            <form class="form-data-action"  action="{{route('product.action')}}" method="POST">
                @csrf
               
                    <div class="form-action-product form-action form-inline py-3">
                        @can('action-san-pham')
                        <select class="form-control info-action mr-1" id="" name="act">
                            <option value="choose">Chọn</option>
                            @foreach ($list_action as $key=>$item)
                                <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary action-product">
                        @endcan
                    </div>
               
                <div class="table-data table-data-product" data-action="{{route('product.pagination')}}">
                        @include('admin/product/pagination')
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
