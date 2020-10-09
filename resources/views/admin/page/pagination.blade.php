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
            <th scope="col">Tiêu đề</th>
            <th scope="col">Người tạo</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Người sửa</th>
            <th scope="col">Ngày sửa</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
       
        @if ($list_pages->total()>0)
            @php
                $count = 0;   
            @endphp
            @foreach ($list_pages as $page)
                    @php
                        $count ++;   
                    @endphp
                    <tr class="element" data-id="{{$page->id}}">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$page->id}}">
                        </td>
                        <td scope="row">{{$count}}</td>
                        <td><a href="{{route('page.edit', $page->id)}}">{{$page->title}}</a></td>
                        <td>{{$page->creator}}</td>
                        <td>{{$page->created_at}}</td>
                        <td>{{$page->editor}}</td>
                        <td>{{$page->updated_at}}</td>
                        <td>
                            @can('page-edit', $page)
                            <a href="{{route('page.edit', $page->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            @endcan
                           
                            @can('page-delete',$page)
                            <a href="{{route('page.delete', $page->id)}}" data-id="{{$page->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
{{$list_pages->links()}}