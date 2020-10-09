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
            <th scope="col">Tên khách hàng</th>
            <th scope="col">Email</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Số điện thoại</th>
            
        </tr>
    </thead>
    <tbody>
        @if(count($customers) >0)
            @php
                $count = 0;    
            @endphp
            @foreach ($customers as $item)
                @php
                    $count++;    
                @endphp
                <tr class="element" data-id="{{$item->id}}">
                    <td scope="row">{{$count}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->address}}</td>
                    <td>{{$item->phone}}</td>
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
{{$customers->links()}}