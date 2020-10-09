@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Thống kê hàng tồn</h5>
            <div class="form-search form-inline">
                <form action="{{route('inventory.search')}}" id="search-form-inventory" class="form-search-result">
                    @csrf
                    <input type="" class="form-control keyword" name="keyword" value="" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
                <div class="table-data-inventory table-data" data-action={{route('inventory.pagination')}}>
                    @include('admin/order/inventory-pagination')
                </div>
            </div>
    </div>
</div>
@endsection