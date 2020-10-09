@extends('layouts.smart')
@php
    $slug =request()->slug;
@endphp
@section('title',"Smart shop - $slug")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                    <div class="breadcrumb-custom">{{Breadcrumbs::render('page.detail', request()->slug) }}</div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="page-wrapper">
                    <div class="title">
                        <h2>{{$page->title}}</h2>
                    </div>
                    <div class="content">
                        <p>{!! $page->content !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection