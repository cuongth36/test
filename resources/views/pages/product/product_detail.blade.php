@extends('layouts.smart')
@section('title',"Smart shop - $products->slug")
@section('content')
 <!-- PRODUCT DETAIL V3 -->
<div class="container">
  <div class="row">
    <div class="col-md-12">
      @if (request()->slug)
        <div class="breadcrumb-custom">{{Breadcrumbs::render('product.detail', request()->slug) }}</div>
      @endif
     
    </div>
  </div>
</div>
 <section class="product-detail-v1">
    <div class="container">
      <div class="product-detail-v1-wrapper">
        <div class="row">
          <div class="col-md-6">
            <div class="product-detail-v3-image">
              <div class="product-detail-v3-order row">
                <div class="order2-prodetail-v3 custom-col-md-10-prod">
                  <div class="slider slider-single change-pr-img">
                  <img src="{{url($products->thumbnail)}}" alt="{{$products->title}}" class="img-fluid img-zoom-product" data-zoom-image="{{url($products->thumbnail)}}">
                  </div>
                </div>
                @if (count($feature_image) > 0)
                  <div class="order1-prodetail-v3 custom-col-md-2-prod">
                    <div class="slider slider-nav">
                      @foreach ($feature_image as $image)
                              @foreach ($image as $value)
                                  @if (!empty($value))
                                    <div class="product-detail-v3-image change-image">
                                    <img src="{{url($value)}}" alt="{{$products->title}}" class="img-fluid change-zoom-image" data-zoom-image="{{url($value)}}" data-image="{{url($value)}}">
                                  </div>
                                  @endif
                              @endforeach
                      @endforeach
                    </div>
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                  <div class="product-detail-v1-info pro-detail-v2-pt">
                    <h1 class="product-detail-v1-title mb-0">{{$products->title}}</h1>
                    
                    <div class="product-detail-v1-meta">
                      <div class="product-detail-v1-price">
                          @if (!empty($products->discount))
                          <span class="cost">{{number_format($products->price, 0, '', '.')}}đ</span>
                          @else
                          <span class="price">{{number_format($products->price, 0, '', '.')}}đ</span>
                          @endif
                          
                          @if (!empty($products->discount))
                          <span class="sale">
                           
                              {{number_format((1-($products->discount/100))*$products->price, 0, '', '.')}}đ  
                          </span>
                          @endif
                      </div>
                    </div>
                    <p class="product-description">
                      {{$products->description}}
                    </p>
                    <form action="{{route('cart.add', $products->id)}}" method="GET" id="form-submit-product">
                      @csrf
                      <div id="form-add-product">
                        <div class="product-detail-v1-action product-info-detail">
                          @if (count($product_color) > 0)
                          <div class="product-color">
                            <span id="title-color">Màu sắc:</span>
                            <ul>
                              @foreach ($product_color as $color)
                                  <li class="color-item">
                                   
                                    <span name="color_product" style="background-color:{{$color->color_code}}" data-toggle="tooltip" title="{{$color->title}}">
                                     @csrf
                                    <input type="radio" name="product-color" value="{{$color->id}}" data-size="{{$color->size_id}}" data-qty ={{$color->amount}} class="color-info">
                                    <input type="hidden" name="slug-product" class="slug-product" value="{{$color->product_slug}}">
                                    <input type="hidden" name="action-change-size" value="{{route('product.change', [$color->product_slug, '.html'])}}" class="action-change-size">
                                    </span>
                                  </li>
                              @endforeach
                            </ul>
                            <div class="error">
                              @error('product-color')
                                <small class="text-danger">{{$message}}</small>    
                              @enderror
                            </div>
                          </div>
                          @endif
                          @include('pages/product/data-size')

                          <div class="product-out-stock">
                            <p>Sản phẩm tạm hết hàng</p>
                          </div>

                          <div class="product-detail-buy">
                            <div class="quanlity qty-custom" id="order-qty">
                              @php
                                $min = 1;
                              @endphp
                              <button type="button" id="minus" class="subtraction" {{$min == 1 ? 'disabled' : ''}}><i class="fa fa-minus"></i></button>
                                <input type="text" class="quanlity-num" name="qty" pattern="[0-9]*" min="1" max="{{$products->quantity}}" value="1" readonly>
                              <button type="button" id="plus" class="addition">
                                <i class="fa fa-plus"></i>
                              </button>
                            </div>
                            <div class="add-product">
                              <button type="submit" id="add-to-cart" class="btn-addproduct">Thêm giỏ hàng</button>
                            </div>
                          </div>

                        </div>
                      </div>
                    </form>
                    <div class="product-detail-v1-attr">
                    <p class="mb-0"><span>Danh mục:</span><a href="{{route('product_category.show', [$products->categories->slug, '.html'])}}"> {{$products->categories->title}}.</a></p>
                    </div>
                  </div>
             
              </div>
            </div>
          </div>
        </div>
       
        <div class="row">
          <div class="col-md-12">
            <div class="product-detail-tabs-content pro-dt-tab-content-pt">
              <nav> 
                <div class="nav nav-fill product-detail-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
         
                  <a class="product-detail-tabs-item active" id="nav-profile-tab" data-toggle="tab" href="#pro-detail-Additionalinfo" role="tab" aria-controls="pro-detail-Additionalinfo" aria-selected="false">Thông tin chi tiết</a>
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="pro-detail-Additionalinfo" role="tabpanel" aria-labelledby="nav-profile-tab">
                    {!! $products->content !!}
                  </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    <section class="popular-product deal-of-the-week product-detail-v1-related js-slider-item">
      <div class="container">
        <div class="row">
          <div class="deal-of-the-week-title box-title">
            <h2 class="dotw-title des-title">Sản phẩm tương tự</h2>
          </div>
        </div>
        <div class="row deal-of-the-week-inner">
          <div class="product-item owl-carousel owl-theme">
            @foreach ($product_release as $item)
                <div class="">
                  <div class="dotw-item box-item">
                    <div class="dotw-item-image box-item-image">
                    <a href="{{route('product.detail', [$item->slug, '.html'])}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
                    </div>
                    <div class="dotw-item-info box-item-info">
                      <h3><a href="{{route('product.detail', [$item->slug, '.html'])}}" class="dotw-item-name item-name">{{Str::limit($item->title,25, '..')}}</a></h3>
                      <div class="dotw-price-rate item-price-rate">
                        <div class="dotw-price item-price">
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
                        @if ($item->product_hot == '1')
                          <div class="percent-hot">
                              Hot
                          </div>
                        @endif
                    </div>
                    
                  </div>
                </div>         
            @endforeach
       
          </div> 
        </div>
      </div>
    </section>
  </section>
@endsection