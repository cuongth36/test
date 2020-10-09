@if (request()->slug)
@if (count($list_category) > 0)
    @foreach ($list_category as $category)
    <div class="col-xl-3 col-md-6 col-lg-4 col-sm-12 col-12 layout">
        <div class="box-item box-item-list-shoppage">
        <div class="box-item-image">
            <a href="{{route('product.detail', [$category->slug, '.html'])}}"><img src="{{url($category->thumbnail)}}" alt="{{$category->title}}"></a>
        </div>
        <div class="box-item-info">
            <a href="{{route('product.detail', [$category->slug, '.html'])}}"><h3 class="item-name m-bottom-0">{{Str::limit($category->title,25, '..')}}</h3></a>
            <div class="item-price-rate">
            <div class="item-price">
                @if (!empty($category->discount))
                <span class="cost">{{number_format($category->price, 0, '', '.')}}đ</span>
                @else
                <span class="price">{{number_format($category->price, 0, '', '.')}}đ</span>
                @endif
                
                @if (!empty($category->discount))
                <span class="sale">
                
                    {{number_format((1-($category->discount/100))*$category->price, 0, '', '.')}}đ
                    
                </span>
                @endif
            </div>
            </div>
                                                                                            
        </div>
        <div class="offer">
            @if (!empty($category->product_discount))
                <div class="percent">{{$category->product_discount}}%</div>
            @endif

            @if ($category->product_hot == '1')
                <div class="percent-hot">
                    Hot
                </div>
            @endif
        
        </div>
        </div>
    </div> 
 
    @endforeach
    @else
    <p class="record-empty">Không tìm thấy sản phẩm</p>
@endif

@else
    @if (count($products) > 0)
        @foreach ($products as $item)
        <div class="col-xl-3 col-md-6 col-lg-4 col-sm-12 col-12 layout">
        <div class="box-item box-item-list-shoppage">
        <div class="box-item-image">
            <a href="{{route('product.detail', [$item->slug, '.html'])}}"><img src="{{url($item->thumbnail)}}" alt="{{$item->title}}"></a>
        </div>
        <div class="box-item-info">
            <a href="{{route('product.detail', [$item->slug, '.html'])}}"><h3 class="item-name m-bottom-0">{{Str::limit($item->title, 25, '..')}}</h3></a>
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

            @if ($item->product_hot == '1')
                <div class="percent-hot">
                    Hot
                </div>
            @endif
        
        </div>
        </div>
        </div>   
        @endforeach
        @else
        <p class="record-empty">Không tìm thấy sản phẩm</p>
    @endif
@endif
