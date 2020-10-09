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
            <th scope="col">Ảnh</th>
            <th scope="col">Tên slider</th>
            <th scope="col">Link</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if(count($sliders) >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($sliders as $item)
                @php
                    $count++;    
                @endphp
                <tr class="element" data-id="{{$item->id}}">
                
                    <td scope="row">{{$count}}</td>
                    <td><img src="{{url($item->thumbnail)}}" alt="{{$item->title }}" class="thumbnail-admin"></td>
                    <td><a href="{{route('slider.edit', $item->id)}}">{{$item->title }}</a></td>
                    
                    <td>{{$item->link}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        @can('slider-edit',$item)
                            <a href="{{route('slider.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endcan

                        @can('slider-delete', $item)
                            <a href="{{route('slider.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
{{$sliders->links()}}