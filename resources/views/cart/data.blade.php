@if (Cart::count() > 0)
<div id="wrapper" class="wp-inner clearfix">
    <div class="section" id="info-cart-wp">
        <div class="section-detail table-responsive">
            <table class="table product-cart">
                <thead>
                    <tr>
                        <td>Mã sản phẩm</td>
                        <td>Ảnh sản phẩm</td>
                        <td>Tên sản phẩm</td>
                        <td>Giá sản phẩm</td>
                        <td>Số lượng</td>
                        <td colspan="2">Thành tiền</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach (Cart::content() as $item)
                        <tr class="cart-row" data-id="{{$item->rowId}}">
                            <td>{{$item->id}}</td>
                            <td>
                            <a href="{{route('product.detail', $item->id)}}" title="" class="thumb">
                                    <img src="{{url($item->options->thumbnail)}}" alt="">
                                </a>
                            </td>

                            <td>
                                <a href="{{route('product.detail', $item->id)}}" title="" class="name-product">{{$item->name}}</a>
                            </td>
                            <td>{{number_format($item->price, 0, '', '.')}}đ</td>

                            <td>
                                <a class="minus-change"><i class="fa fa-minus"></i></a>
                                <input type="text" name="qty[{{$item->rowId}}]" value="{{$item->qty}}" min='1' data-max="{{$item->options->quatity}}" class="number-order-quantity" readonly>
                                @csrf
                                <input type="hidden" name="action-update-cart" class="action-update-cart" data-action="{{route('cart.update',$item->rowId)}}" data-row={{$item->rowId}} data-id={{$item->id}}>
                                <a  class="plus-change"><i class="fa fa-plus"></i></a>
                            </td>
                           
                            <td><span class="total">{{ number_format($item->total, 0 , '', '.') }}</span>đ</td>
                            <td>
                            <a href="{{route('cart.delete', $item->rowId)}}" title="" class="del-product" data-id={{$item->rowId}} data-redirect={{route('cart.list')}}><i class="fa fa-trash-o cart-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                   
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="clearfix">
                                <p id="total-price" class="fl-right">Tổng giá: <span><strong class="sub-total">{{Cart::total()}}</strong>đ</span></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div class="clearfix">
                                <div class="fl-right">
                                <a href="{{route('cart.pre_checkout')}}" title="" id="checkout-cart">Thanh toán</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="section" id="action-cart-wp">
        <div class="section-detail">
            <a href="{{url('/')}}" title="" id="buy-more">Mua tiếp</a><br/>
            <a href="{{route('cart.destroy')}}" title="" id="delete-cart">Xóa giỏ hàng</a>
        </div>
    </div>
</div>
@else
    <div class="wp-inner">
        <p>Không có sản phẩm nào trong giỏ hàng!</p>
    </div>
@endif