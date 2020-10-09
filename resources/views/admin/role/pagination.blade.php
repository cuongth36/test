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
            
            <th scope="col">STT</th>
            <th scope="col">Tên vai trò</th>
            <th scope="col">Mô tả</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if(count($roles) >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($roles as $item)
                @php
                    $count++;    
                @endphp
                <tr class="element" data-id="{{$item->id}}">
                   
                    <td scope="row">{{$count}}</td>
                    <td><a href="{{route('role.edit', $item->id)}}">{{ $item->name }}</a></td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        @can('role-edit', $item)
                        <a href="{{route('role.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white " type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endcan
                        
                        @can('role-delete', $item)
                        <a href="{{route('role.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record " type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
{{$roles->links()}}