@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật đơn hàng
        </div>
        <div class="card-body">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                      
                        <th scope="col">#</th>
                        <th scope="col">Mã</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Giá giảm</th>
                        <th scope="col">Thời gian</th>
                    </tr>
                </thead>
                @if (!empty($order))
                    <tbody>
                        @php
                            $count = 0;
                        @endphp
                            @foreach ($order as $value)
                              
                                @php
                                    $count++;
                                @endphp
                            <tr>
                                
                                <td>{{$count}}</td>
                                <td>{{$value->order_code}}</td>
                                <td>
                                    {{$value->name}} <br>
                                    {{$value->phone}}
                                </td>
                                <td>{{$value->title}}</td>
                                <td>{{$value->qty}}</td>
                                <td>{{number_format($value->price,0, '','.')}}đ</td>
                                <td>{{!empty($value->discount) ? number_format((1-(5/100))*$value->price,0, '','.') : '0'}}đ</td>
                                <td>{{$value->created_at}}</td>
                            </tr>
                            @endforeach
                    </tbody>
                @endif
            </table>
            <form action="{{route('order.update',$value->id)}}" method="POST">
                @csrf
                <div class="form-group">
                    <hr>
                    <p><strong>Cập nhật trạng thái đơn hàng</strong></p>
                    <select name="update-status" id="" class="custom-select">
                        <option value="0" {{$value->status == '0' ? 'selected' : ''}}>Đang xử lý</option>
                        <option value="1" {{$value->status == '1' ? 'selected' : ''}}>Hoàn thành</option>
                        <option value="2" {{$value->status == '2' ? 'selected' : ''}}>Hủy</option>
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Cập nhật">
            </form>
            @endsection
        </div>
    </div>
</div>