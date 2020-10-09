@extends('layouts.smart')
@php
    $slug = request()->slug;
@endphp
@section('title',"Smart shop-$slug")
@section('content')
<section id="aa-blog-archive">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb-custom">{{Breadcrumbs::render('post.detail', request()->slug) }}</div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="aa-blog-archive-area">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 order-item-2">
                <!-- Blog details -->
                <div class="aa-blog-content aa-blog-details blog-list">
                  @foreach ($posts as $item)     
                    <article class="aa-blog-content-single">               
                        <h2>{{$item->title}}</h2>
                        <div class="aa-article-bottom">
                          <div class="aa-post-author">
                            Tác giả: <span>{{$item->user->name}}</span>
                          </div>
                          <div class="aa-post-date">
                            {{$item->created_at}}
                          </div>
                        </div>
                        <p>{{$item->content}}</p>
                    
                    </article>
                    @endforeach
                 
                </div>
              </div>

              <!-- blog sidebar -->
              <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 order-item-1 fix-sidebar js-fix-sidebar">
                <aside class="aa-blog-sidebar">
                  <div class="aa-sidebar-widget">
                    <h3>Sản phẩm bán chạy</h3>
                    <div class="aa-recently-views">
                      <ul>
                        @foreach ($product_seller as $product)
                        
                          <li class="article-item">
                            <div class="thumb">
                              <a class="aa-cartbox-img" href="{{route('product.detail', [$product->slug,'.html'])}}"><img src="{{url($product->thumbnail)}}" alt="{{$product->title}}"></a>
                            </div>
                            <div class="aa-cartbox-info">
                              <h4><a href="{{route('product.detail', [$product->slug, '.html'])}}">{{Str::limit($product->title, 15, '..')}}</a></h4>
                              <p>{{number_format($product->price, 0,' ','.')}}đ</p>
                            </div>                    
                          </li>  
                        @endforeach                         
                      </ul>
                    </div>                            
                  </div>
                </aside>
              </div>
            </div>           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Button sidebar mobie -->
  <div class="button-filter js-filter">
    <span class="fa fa-filter"></span>
  </div>
@endsection