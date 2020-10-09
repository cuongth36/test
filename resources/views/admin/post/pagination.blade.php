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
            <th scope="col">Tiêu đề</th>
            <th scope="col">Danh mục</th>
            <th scope="col">Người tạo</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Người sửa</th>
            <th scope="col">Ngày sửa</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if(count($posts) >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($posts as $item)
                @php
                    $count++;    
                @endphp
                
                <tr class="element" data-id="{{$item->id}}">
                    <td>
                        <input type="checkbox"  name="list_check[]" value="{{$item->id}}">
                    </td>
                    <td scope="row">{{$count}}</td>
                    <td><img src="{{ url($item->thumbnail)}}" alt=""></td>
                    <td><a href="{{route('post.edit', $item->id)}}">{{$item->title}}</a></td>
                    @foreach ($cate as $value)
                        @if($item->category_id == $value->id)
                             <td>{{str_repeat('- ', $value->level) . $value->title }}</td>
                        @endif
                    @endforeach
                    <td>{{$item->creator}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->editor}}</td>
                    <td>{{$item->updated_at}}</td>
                    <td>
                        @can('post-edit', $item)
                             <a href="{{route('post.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endcan
                        
                        @can('post-delete', $item)
                            <a href="{{route('post.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
{{$posts->links()}}
