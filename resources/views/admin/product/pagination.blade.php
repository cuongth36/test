<div class="loader-wrapper">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
</div>

<table class="table table-striped table-checkall">
    <thead>
        <tr>
            <th scope="col">
                <input name="checkall" type="checkbox">
            </th>
            <th scope="col">STT</th>
            <th scope="col">Ảnh</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Giá tiền</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Danh mục</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if($products->total() >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($products as $item)
                @php
                    $count++;    
                @endphp
                
                <tr class="element" data-id="{{$item->id}}">
                    <td>
                        <input type="checkbox"  name="list_check[]" value="{{$item->id}}">
                    </td>
                    <td scope="row">{{$count}}</td>
                    <td><img src="{{ url($item->thumbnail)}}" alt=""></td>
                    <td><a href="{{route('product.edit', $item->id)}}">{{$item->title}}</a></td>
                    <td>{{number_format($item->price, 0 , '', '.')}}</td>
                    <td>
                        @if (!empty($product_qty))
                            @foreach ($product_qty as $qty)
                                @if ($qty->product_id == $item->id)
                                    {{$qty->total_qty}}
                                @endif
                            @endforeach
                        @endif
                       
                    </td>
                    @foreach ($cate as $value)
                        @if($item->category_id == $value->id)
                             <td>{{$value->title }}</td>
                        @endif
                    @endforeach
                    <td>{{$item->created_at}}</td>
                    <td>
                        <span class="badge @foreach ($product_qty as $qty)
                                @if ($qty->product_id == $item->id)
                                    {{$qty->total_qty > 0 ? 'badge-success' : 'badge-danger'}}
                                @endif
                            @endforeach">
                            @foreach ($product_qty as $qty)
                                @if ($qty->product_id == $item->id)
                                    {{$qty->total_qty > 0 ? 'Còn hàng' : 'Hết hàng'}}
                                @endif
                            @endforeach
                        </span>
                    </td>
                    <td>
                        @can('product-edit', $item)
                            <a href="{{route('product.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endcan
                       
                        @can('product-delete', $item)
                            <a href="{{route('product.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                        @endcan

                    </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td class="bg-white" colspan="4">Không tìm thấy bản ghi</td>
            </tr>
        @endif
        <tr>
            <td colspan="7" class="result bg-white" ></td>
        </tr>
       
    </tbody>
</table>
{{$products->links()}}
