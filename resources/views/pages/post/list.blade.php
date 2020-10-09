@extends('layouts.smart')
@section('title','Smart shop - Tin tức')
@section('content')
<section id="aa-blog-archive">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb-custom">{{Breadcrumbs::render('post.show.list', request()->slug) }}</div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="aa-blog-archive-area">
            <div class="row">
              <div class="col-md-9 col-lg-9 order-item-2">
                <div class="aa-blog-content blog-list">
                  <div class="row">
                    @if (count($posts))
                          @foreach ($posts as $item)
                         
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="row aa-blog-content-single">
                                    <div class="col-md-5">
                                      <figure class="aa-blog-img">
                                        <a href="{{route('post.detail', [$item->slug, '.html'])}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
                                      </figure>
                                    </div>
                                    <div class="col-md-7">
                                      <h4><a href="{{route('post.detail', [$item->slug, '.html'])}}">{{$item->title}}</a></h4>
                                      <p>{{str_limit($item->description,  20, ' (...)')}}</p>
                                      <div class="aa-article-bottom">
                                          <div class="aa-post-author">
                                          Tác giả: <span>{{$item->user->name}}</span>
                                          </div>
                                          <div class="aa-post-date">
                                              {{$item->created_at}}
                                          </div>
                                      </div>
                                    </div>
                                </div>
                              </div>
                          @endforeach
                    @else
                              <p class="empty-record">Không tìm thấy bản ghi</p>
                    @endif
                      
                  </div>
                </div>
                {{$posts->links()}}
              </div>
              <div class="col-md-3 col-lg-3 order-item-1">
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
                              <h4><a href="{{route('product.detail', [$product->slug, '.html'])}}">{{Str::limit($product->title, 20, '..')}}</a></h4>
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
@endsection