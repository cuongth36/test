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
            <th>
                <input type="checkbox" name="checkall">
            </th>
            <th scope="col">#</th>
            <th scope="col">Mã</th>
            <th scope="col">Khách hàng</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Thành tiền</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Thời gian</th>
            <th scope="col">Tác vụ</th>
        </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @php
                $count = 0;
                $total = 0;
            @endphp
            @foreach ($orders as $item)
                    @php
                        $count++;
                        $total = ($item->qty)*($item->price);
                    @endphp
                <tr class="element" data-id="{{$item->id}}">
                    <td>
                        <input type="checkbox" name="list_check[]" value="{{$item->id}}">
                    </td>
                    <td>{{$count}}</td>
                    <td>{{$item->order_code}}</td>
                    <td>
                        {{$item->name}} <br>
                        {{$item->phone}}
                    </td>
                    <td>{{$item->qty}}</td>
                    <td>{{number_format($total,0, '','.')}}đ</td>
                    <td><span class="badge badge-warning">@if($item->status == '0'){{'Đang xử lý'}}@elseif($item->status == '1'){{'Hoàn thành'}}@else{{'Hủy'}} @endif</span></td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        @can('order-edit', $item)
                           
                            <a href="{{route('order.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endcan
                        
                        @can('order-delete', $item)
                            @if ($item->status != '1')
                                <a href="{{route('order.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                            @endif
                        @endcan
                       
                    </td>
                </tr>
                
            @endforeach
        @else   
            <tr >
                <td colspan="7" class="bg-white">Không tìm thấy bản ghi nào?</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    <ul class="pagination-info">
       @php
            if(request()->status == 'pendding'){
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $url=request()->url();
                $status = request()->status;
                if($page > 1){
                    $active = "";
                   
                    $prev = (int) $page-1;
                    if($page == $prev) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$prev' class='page-link'>‹</a></li>";
                }
                for ($i=1; $i <=$num_page[1] ; $i++) { 
                    $active = "";
                  
                    if($i == $page) $active= "class='active' ";
                    echo  "<li {$active}><a href='$url?$status&page=$i' class='page-link'>$i</a></li>";
                }  

                if($page < $num_page[1]){
                    $active = "";
                    
                    $next = (int) $page+1;
                    if($page == $next) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$next' class='page-link'>›</a></li>";
                }
            }
            
            if(request()->status == 'active'){
                
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $url=request()->url();
                $status = request()->status;
                if($page > 1){
                    $active = "";
                    $prev = (int) $page-1;
                    if($page == $page-1) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$prev' class='page-link'>‹</a></li>";
                   
                }

                for ($i=1; $i <=$num_page[0] ; $i++) { 
                    $active = "";
                  
                    if($i == $page){
                        $active= "class='active' "; 
                    } 
                    echo  "<li {$active}><a href='$url?$status&page=$i' class='page-link'>$i</a></li>";

                } 

                if($page < $num_page[0]){
                    $active = "";
                   
                    $next = (int) $page+1;
                    if($page == $page+1) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$next' class='page-link'>›</a></li>";
                }
            }

           if(request()->status == 'trash'){
                 $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                 $url=request()->url();
                 $status = request()->status;
                if($page > 1){
                    $active = "";
                  
                    $prev = (int) $page-1;
                    if($page == $page-1) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$prev' class='page-link'>‹</a></li>";
                }

                for ($i=1; $i <=$num_page[2] ; $i++) { 
                    $active = "";
                   
                    if($i == $page) $active= "class='active' ";
                    echo  "<li {$active}><a href='$url?$status&page=$i' class='page-link'>$i</a></li>";
                    
                } 

                if($page < $num_page[2]){
                    $active = "";
                   
                    $next = (int) $page+1;
                    if($page == $page+1) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?$status&page=$next' class='page-link'>›</a></li>";
                }
           }

           

       @endphp
        
    </ul>
</div>