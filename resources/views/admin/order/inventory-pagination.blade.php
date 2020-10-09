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
            <th scope="col">#</th>
            <th scope="col">Tên hàng</th>
            <th scope="col">Size</th>
            <th scope="col">Màu sắc</th>
            <th scope="col">Số lượng nhập</th>
            <th scope="col">Số lượng bán</th>
            <th scope="col">Số lượng còn</th>
        </tr>
    </thead>
    <tbody>
        @if (count($inventory) > 0)
            @php
                $count = 0;
               
            @endphp
            {{-- {{dd($inventory)}} --}}
            @foreach ($inventory as $item)
                    @php
                        $count++;
                        $qty_remaining = (int)$item->amount  - (int)$item->qty;
                    @endphp
                <tr class="element">
                    <td>{{$count}}</td>
                    <td>
                        {{$item->title}}
                    </td>
                    <td>
                        {{$item->size_name}}
                    </td>
                    <td>
                        {{$item->color_name}}
                    </td>
                    <td>{{$item->amount}}</td>
                    <td>{{$item->qty}}</td>
                    <td>{{$qty_remaining}}</td>
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
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $url=request()->url();
                if($page > 1){
                    $active = "";
                    
                    $prev = (int) $page-1;
                    if($page == $prev) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?page=$prev' class='page-link'>‹</a></li>";
                }
                for ($i=1; $i <=$num_inventory ; $i++) { 
                    $active = "";
                   
                    if($i == $page) $active= "class='active' ";
                    echo  "<li {$active}><a href='$url?page=$i' class='page-link'>$i</a></li>";
                }  

                if($page < $num_inventory){
                    $active = "";
                   
                    $next = (int) $page+1;
                    if($page == $next) $active= "class='active' ";
                    echo "<li {$active}><a href='$url?page=$next' class='page-link'>›</a></li>";
                }
       @endphp
        
    </ul>
</div>