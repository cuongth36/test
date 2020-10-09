@extends('layouts.admin')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                <div class="card-body">
                <h5 class="card-title">{{$info_order[0]}}</h5>
                    <p class="card-text">Đơn hàng giao dịch thành công</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐANG XỬ LÝ</div>
                <div class="card-body">
                    <h5 class="card-title">{{$info_order[1]}}</h5>
                    <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">DOANH SỐ</div>
                <div class="card-body">
                    <h5 class="card-title">{{number_format($total_inventory, 0 , ' ', '.')}}vnđ</h5>
                    <p class="card-text">Doanh số hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG HỦY</div>
                <div class="card-body">
                    <h5 class="card-title">{{$info_order[2]}}</h5>
                    <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end analytic  -->
    <div class="card">
        <div class="card-header font-weight-bold">
            ĐƠN HÀNG MỚI
        </div>
        <div class="card-body">
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
                                <td>{{$count}}</td>
                                <td>{{$item->order_code}}</td>
                                <td>
                                    {{$item->name}} <br>
                                    {{$item->phone}}
                                </td>
                                <td>{{$item->qty}}</td>
                                <td>{{number_format($total,0, '','.')}}đ</td>
                            <td><span class="badge {{$item->status == '1' ? 'bg-primary' : 'badge-warning'}}">@if($item->status == '0'){{'Đang xử lý'}}@elseif($item->status == '1'){{'Hoàn thành'}}@else{{'Hủy'}} @endif</span></td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    @if ($item->status != '1')
                                    <a href="{{route('order.edit', $item->id)}}" class=" btn btn-success btn-sm rounded-0 text-white @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    @endif
                                    <a href="{{route('order.delete', $item->id)}}" data-id="{{$item->id}}"  class="btn btn-danger btn-sm rounded-0 text-white delete-record @if(request()->status == 'trash') {{'disabled'}} @endif" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
                      
                            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                            $url=request()->url();
                            $status = request()->status;
                            if($page > 1){
                                $active = "";
                                $prev = (int) $page-1;
                                if($page == $page-1) $active= "class='active' ";
                                echo "<li {$active}><a href='$url?page=$prev' class='page-link'>‹</a></li>";
                               
                            }
            
                            for ($i=1; $i <=$num_page ; $i++) { 
                                $active = "";
                              
                                if($i == $page){
                                    $active= "class='active' "; 
                                } 
                                echo  "<li {$active}><a href='$url?page=$i' class='page-link'>$i</a></li>";
            
                            } 
            
                            if($page < $num_page){
                                $active = "";
                               
                                $next = (int) $page+1;
                                if($page == $page+1) $active= "class='active' ";
                                echo "<li {$active}><a href='$url?page=$next' class='page-link'>›</a></li>";
                            }
            
                   @endphp
                    
                </ul>
            </div>
        </div>
    </div>

</div>    
@endsection