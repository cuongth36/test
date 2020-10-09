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
            <h5 class="m-0 ">Danh mục vai trò</h5>
            <div class="form-search form-inline">
                <form action="{{route('role.search')}}" id="search-form-role" class="form-search-result">
                    <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
                <div class="table-data table-data-role" data-action={{route('role.pagination')}}>
                        @include('admin/role/pagination')
                </div>
        </div>
    </div>
</div>
@endsection
