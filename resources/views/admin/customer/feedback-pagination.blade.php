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
            <th scope="col">Tên</th>
            <th scope="col">Email</th>
            <th scope="col">Tiêu đề</th>
            <th scope="col">Nội dung</th>
            <th scope="col">Người xử lý</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
       
        @if ($contacts->total()>0)
            @php
                $count = 0;   
            @endphp
            @foreach ($contacts as $contact)
                    @php
                        $count ++;   
                    @endphp
                    <tr class="element" data-id="{{$contact->id}}">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$contact->id}}">
                        </td>
                        <td scope="row">{{$count}}</td>
                        <td><a href="">{{$contact->name}}</a></td>
                        <td>{{$contact->email}}</td>
                        <td>{{$contact->title}}</td>
                        <td>{{$contact->content}}</td>
                        <td>{{!empty($contact->user->name) ? $contact->user->name : '' }}</td>
                        <td>{{$contact->created_at}}</td>
                        <td>
                            {{-- @can('contact-edit', $contact) --}}
                            <a href="{{route('admin.feedback.edit', $contact->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            {{-- @endcan --}}
                            
                            {{-- @can('contact-delete', $contact) --}}
                            <a href="{{route('admin.feedback.delete', $contact->id)}}" data-id="{{$contact->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            {{-- @endcan --}}
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
{{$contacts->links()}}