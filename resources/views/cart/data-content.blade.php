
 <table class="table cart-table space-80">
    <thead>
        <tr>
            <th class="produc-thumbnail">Ảnh</th>
            <th class="product-photo">Sản phẩm</th>
            <th class="product-photo">Giá</th>
            <th class="product-quantity">Số lượng</th>
            <th class="total-price">Thành tiền</th>
            <th class="product-remove">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @foreach (Cart::content() as $item)
      
            <tr class="item_cart cart-row" data-id={{$item->rowId}}>
                <td class="product-photo"><a href="{{route('product.detail', [$item->options['slug'], '.html'] )}}"><img src="{{$item->options['thumbnail']}}" alt="{{$item->name}}" height="100" width="100"></a></td>
                <td class="produc-name">
                    <a href="{{route('product.detail', [$item->options['slug'], '.html'] )}}" title="">{{$item->name}}</a>
                </td>
                <td class="produc-name">
                    {{number_format($item->price, 0, '', '.')}}đ
                </td>
                <td class="product-quantity">
                        <div class="product-signle-options product_15 clearfix">
                            <div class="selector-wrapper size">
                                <div class="quantity chang-qty">
                                    <button class="minus minus-change custom-button" {{$item->qty == 1 ? 'disabled' : ''}}><i class="fa fa-minus"></i></button>
                                    <input data-step="1" value="{{$item->qty}}" name="qty[{{$item->rowId}}]" data-product-id="{{$item->id}}"  min="1" max="{{$item->options['total']}}" title="Qty" class="qty number-qty number-order-quantity"  type="text" readonly>
                                    <button class="plus plus-change custom-button" {{$item->options['total'] == $item->qty ? 'disabled' : ''}}><i class="fa fa-plus"></i></button>
                                    @csrf
                                    <input type="hidden" name="action-update-cart" class="action-update-cart" data-action="{{route('cart.update')}}" data-id={{$item->rowId}}>
                                    <input type="hidden" name="price" value="{{$item->price}}" class="data-price">
                                </div>
                            </div>
                        </div>
                </td>
                <td class="total-price price-total">{{number_format($item->total, 0, '', '.')}}đ</td>
                <td class="product-remove">
                <a class="remove btn-danger" href="{{route('cart.delete', $item->rowId)}}" title="Xóa">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach 
    </tbody>
</table>
