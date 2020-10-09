@extends('layouts.smart')
@section('title','Smart shop - Trang chủ')
@section('slider')
<div class="container-fluid">
  <div class="row">
    <div class="slider-wrapper">
      <div class="slider-item owl-carousel owl-theme">
        @foreach ($list_sliders as $slider)
            <div class="item">
                <div class="thumbnail">
                    <a href="{{$slider->link}}">
                      <img src="{{url($slider->thumbnail)}}" alt="{{$slider->title}}">
                    </a>
                </div>
            </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<section class="categories padding-section">
  <div class="container">
    <div class="row">
      <div class="col-xl-12 col-lg-12 cate-left">
        <div class="row clear">
          @if (!empty($category_product))
              @foreach ($category_product as $item)
              <div class="col-md-4 gutter">
                <a href="{{route('product_category.show', [$item->slug, '.html'])}}">
                  <div class="categories-item">
                    <img src="{{url($item->thumbnail)}}" alt="{{$item->title}}">
                    <div class="categories-info">
                      <h4 class="categories-info-name">{{$item->title}}</h4>
                      <p class="categories-info-count">
                        {{-- ({{$item->product_qty}} sản phẩm) --}}
                      </p>
                    </div>  
                  </div>
                </a>
              </div>
            @endforeach
          @endif
        </div>
      </div>
      
    </div>
  </div>
</section>
@if (count($product_seller) >= 4)
<section class="popular-product padding-section js-slider-item">
  <div class="container">
    <div class="row">
        <div class="box-title">
          <h2 class="des-title">Sản phẩm bán chạy</h2>
        </div>
    </div>
    <div class="row">
        <div class="product-item owl-carousel owl-theme">
          @foreach ($product_seller as $product)
            <div class="box-item">
              <div class="box-item-image">
                <a href="{{route('product.detail', [$product->product_slug, '.html'])}}"><img src="{{url($product->product_thumbnail)}}" alt="{{$product->product_title}}"></a>
              </div>
              <div class="box-item-info">
                <h3 class=""><a href="{{route('product.detail', [$product->product_slug, '.html'])}}" class="item-name">{{Str::limit($product->product_title, 25, '..')}}</a></h3>
                <div class="item-price-rate">
                  <div class="item-price">
                    @if (!empty($product->product_discount))
                    <span class="cost">{{number_format($product->product_price, 0, '', '.')}}đ</span>
                    @else
                    <span class="price">{{number_format($product->product_price, 0, '', '.')}}đ</span>
                    @endif
                    <span class="sale">
                      @if (!empty($product->product_discount))
                        {{number_format((1-($product->product_discount/100))*$product->product_price, 0, '', '.')}}đ
                      @endif
                    
                  </span>
                  </div>
                </div>                                                                                         
              </div>
              <div class="offer">
                  @if (!empty($product->product_discount))
                    <div class="percent">-{{($product->product_discount)}}%</div>
                  @endif
                  @if ($product->product_hot == '1')
                    <div class="percent-hot">Hot</div>
                  @endif
              </div>
            </div>
            @endforeach
        </div>
       
    </div>
  </div>
</section>
@endif

<section class="deal-of-the-week padding-section popular-product js-slider-item">
  <div class="container">
    <div class="row">
      <div class="deal-of-the-week-title box-title">
        <h2 class="dotw-title des-title">Sản phẩm nổi bật </h2>
      </div>
    </div>
    <div class="row deal-of-the-week-inner">
        <div class="product-item owl-carousel owl-theme">
          @foreach ($product_hot as $item)
          <div class="dotw-item box-item">
            <div class="dotw-item-image box-item-image">
              <a href="{{route('product.detail', [$item->slug, '.html'])}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
            </div>
            <div class="dotw-item-info box-item-info">
              <h3 class=""><a href="{{route('product.detail', [$item->slug, '.html'])}}" class="dotw-item-name item-name">{{Str::limit($item->title, 25 , '..')}}</a></h3>
              <div class="dotw-price-rate item-price-rate">
                <div class="dotw-price item-price">

                    @if (!empty($item->discount))
                    <span class="cost">{{number_format($item->price, 0, '', '.')}}đ</span>
                    @else
                    <span class="price">{{number_format($item->price, 0, '', '.')}}đ</span>
                    @endif
                    
                    <span class="sale">
                      @if (!empty($item->discount))
                        {{number_format((1-($item->discount/100))*$item->price, 0, '', '.')}}đ
                      @endif
                     
                    </span>
                </div>
              </div>                                                                                         
            </div>

           
            <div class="offer">
              @if (!empty($item->discount))
                 <div class="percent">-{{$item->discount}}%</div>
               @endif
               @if ($item->product_hot == '1')
                 <div class="percent-hot">Hot</div>
               @endif
           </div>
          </div>
          @endforeach
        </div>
    </div>
  </div>
</section>

{{-- <section class="deal-of-the-week padding-section popular-product js-slider-item">
  <div class="container">
    <div class="row">
      <div class="deal-of-the-week-title box-title">
        <h2 class="dotw-title des-title">Tablet nổi bật</h2>
      </div>
    </div>
    <div class="row deal-of-the-week-inner">
        <div class="product-item owl-carousel owl-theme">
            @foreach ($tablet_category as $item)
            <div class="dotw-item box-item">
              <div class="dotw-item-image box-item-image">
                <a href="{{route('product.detail', [$item->slug, '.html'] )}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
              </div>
              <div class="dotw-item-info box-item-info">
                <h3 class=""><a href="{{route('product.detail', [$item->slug, '.html'])}}" class="dotw-item-name item-name">{{$item->title}}</a></h3>
                <div class="dotw-price-rate item-price-rate">
                  <div class="dotw-price item-price">

                      @if (!empty($item->discount))
                      <span class="cost">{{number_format($item->price, 0, '', '.')}}đ</span>
                      @else
                      <span class="price">{{number_format($item->price, 0, '', '.')}}đ</span>
                      @endif
                      
                      <span class="sale">
                        @if (!empty($item->discount))
                          {{number_format((1-(5/100))*$item->price, 0, '', '.')}}đ
                        @endif
                      
                      </span>
                  </div>
                </div>                                                                                         
              </div>

              @if ($item->product_hot)
                <div class="offer">
                  <div class="label">Mới</div>
                </div>
              @endif
            </div>
            @endforeach
        </div>
    </div>
  </div>
</section> --}}
  <!-- Blog & New V2 -->
  <section class="blog-post blog-new-wrapper padding-section js-slider-item">
    <div class="box-title">
      <h2 class="dotw-title des-title">Tin tức nổi bật</h2>
    </div>
    <div class="container">
      <div class="row">
           
            <div class="blog-slider-item owl-carousel owl-theme">
                @foreach ($post as $item)
                  <div class="blog-post-list blog-slider">
                        <div class="blog-post-item blog-post-item-pdb">
                          <div class="blog-post-item-panel">
                            <div class="blog-thum">
                            <a href="{{route('post.detail', [$item->slug, '.html'])}}">
                                  <img src="{{url($item->thumbnail)}}" alt="{{$item->title}}">
                              </a>
                            </div>
                            <div class="blog-info">
                              <div class="blog-meta">
                                <span class="blog-datetime"><i class="blog-icon lnr lnr-calendar-full"></i>{{$item->created_at}}</span>
                                <span class="blog-comment"><i class="blog-icon fa fa-user"></i> {{$item->user->name}}</span>
                              </div>
                              <h2 class="blog-title">
                                <a href="{{route('post.detail', [$item->slug,'.html'])}}" class="blog-title-link">{{Str::limit($item->title, 25 , '..')}}</a>
                              </h2>
                              <div class="blog-content">
                                {{ str_limit($item->description, $limit = 80, $end = '...')}}
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
      </div>
     
     
    </div>
  </section>   
  
@endsection

