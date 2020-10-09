@extends('layouts.admin')

@section('content')
<div id="content" class="loader-absolute">
   <div class="loader-wrapper">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
   </div>
</div>
   @include('admin/order/revenue-data')
@endsection
    


