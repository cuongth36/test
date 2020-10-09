@extends('layouts.smart')
@section('title','Tìm kiếm')
@section('content')
<section class="popular-product product-shoppage-fullwidth ">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <h3 class="title-search">Tìm thấy <strong>{{count($products)}}</strong> kết quả:</h3>
        </div>
      </div>
      <div class="row">
          @foreach ($products as $item)
          <div class="col-xl-3 col-md-6 col-lg-4 col-sm-6 col-12 layout">
            <div class="box-item box-item-list-shoppage">
              <div class="box-item-image">
              <a href="{{route('product.detail' , [$item->slug, '.html'])}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
              </div>
              <div class="box-item-info">
                <a href="{{route('product.detail' , [$item->slug, '.html'])}}"><h3 class="item-name m-bottom-0">{{$item->title}}</h3></a>
                <div class="item-price-rate">
                    <div class="item-price">
                        @if (!empty($item->discount))
                          <span class="cost">{{number_format($item->price, 0, '', '.')}}đ</span>
                          @else
                          <span class="price">{{number_format($item->price, 0, '', '.')}}đ</span>
                          @endif
                          
                          @if (!empty($item->discount))
                          <span class="sale">
                              {{number_format((1-($item->discount/100))*$item->price, 0, '', '.')}}đ
                          </span>
                          @endif
                    </div>   
                </div>                                                                  
              </div>
              <div class="offer">
                @if (!empty($item->discount))
                    <div class="percent">-{{$item->discount}}%</div>
                @endif
            </div>
            </div>
          </div>
          @endforeach
        
      </div>

      @if (count($products) > 8))
          {{$products->links()}}
      @endif
     
    </div>
  </section>
@endsection