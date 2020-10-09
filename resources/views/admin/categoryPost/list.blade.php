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
                <form action="{{route('category.search')}}" id="search-form-category-post" class="form-search-result">
                    <input type="" class="form-control keyword" name="keyword" value="{{request()->input('keyword')}}" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary btn-search">
                </form>
            </div>
        </div>
        <div class="card-body">
                <div class="table-data table-data-category-post">
                    <div class="loader-wrapper">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                
                                <th scope="col">STT</th>
                                <th scope="col">Tên danh mục</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($categories) >0)
                                @php
                                    $count = 0;    
                                @endphp
                                @foreach ($categories as $item)
                                    @php
                                        $count++;    
                                    @endphp
                                    <tr class="element" data-id="{{$item->id}}">
                                       
                                        <td scope="row">{{$count}}</td>
                                        <td><a href="{{route('category.edit', $item->id)}}">{{str_repeat('- ', $item->level) . $item->title }}</a></td>
                                        <td>{{$item->slug}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            @can('post-category-edit', $item)
                                            <a href="{{route('category.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            
                                            @can('post-category-delete', $item)
                                            <a href="{{route('category.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            @endcan
                                            
                                        </td>
                    
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="bg-white" colspan="4">Không tìm thấy bản ghi</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="7" class="result bg-white" ></td>
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
@endsection
