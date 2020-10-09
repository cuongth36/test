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
            <th scope="col">Tên màu</th>
            <th scope="col">Người tạo</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
       
        @if ($sizes->total()>0)
            @php
                $count = 0;   
            @endphp
            @foreach ($sizes as $size)
                    @php
                        $count ++;   
                    @endphp
                    <tr class="element" data-id="{{$size->id}}">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$size->id}}">
                        </td>
                        <td scope="row">{{$count}}</td>
                        <td><a href="{{route('size.edit', $size->id)}}">{{$size->name}}</a></td>
                        <td>{{$size->creator}}</td>
                        <td>{{$size->created_at}}</td>
                        <td>
                            @can('product-size-edit', $size)
                            <a href="{{route('size.edit', $size->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            @endcan
                           
                            @can('product-size-delete', $size)
                            <a href="{{route('size.delete', $size->id)}}" data-id="{{$size->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            @endcan
                        </td>

                    </tr>
            @endforeach
        @else
                    <tr >
                        <td colspan="7" class="bg-white">Không tìm thấy bản ghi nào?</td>
                    </tr>
        @endif
        <tr>
            <td colspan="7" class="result bg-white" ></td>
        </tr>
        
    </tbody>
</table>
{{$sizes->links()}}