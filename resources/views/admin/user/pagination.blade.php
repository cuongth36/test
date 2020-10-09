
<div class="loader-wrapper">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>
<table class="table table-striped table-checkall table-user">
    <thead>
        <tr>
            <th>
                <input type="checkbox" name="checkall">
            </th>
            <th scope="col">STT</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Quyền</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if (count($users) >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($users as $user)
                @php
                    $count++;    
                @endphp
                <tr class="element" data-id="{{$user->id}}">
                    <td>
                    <input type="checkbox" name="list_check[]" value="{{$user->id}}">
                    </td>
                    <td scope="row">{{$count}}</td>
                    <td><a href="{{route('user.edit', $user->id)}}">{{$user->name}}</a></td>
                    <td>{{$user->email}}</td>
                    <td>
                        @php
                            $list_roles = [];
                        @endphp
                        @foreach ($user->roles as $item)
                            @php
                                $list_roles[] = $item->name;
                            @endphp
                        @endforeach
                        @php
                           $user_role = implode(',', $list_roles);
                           echo $user_role = Str::limit($user_role, 80 , '...')
                        @endphp
                    </td>
                    <td>{{$user->created_at}}</td>
                    <td>
                       
                            @can('user-edit', $user)
                                <a href="{{route('user.edit', $user->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                            @endcan
                            
                            @can('user-delete', $user)
                                <a href="{{route('user.delete', $user->id)}}" data-id="{{$user->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            @endcan
                       
                    </td>
                </tr>
            @endforeach
         @else
            <tr>
                <td colspan="7" class="bg-white">Không tìm thấy bản ghi</td>
            </tr>
        @endif
        <tr>
            <td colspan="7" class="result bg-white" ></td>
        </tr>
        
    </tbody>
</table>
{{$users->links()}}