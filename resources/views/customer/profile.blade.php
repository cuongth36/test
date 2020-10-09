@extends('layouts.smart')
@section('title','Smart shop - Thông tin tài khoản')
@section('content')
<div class="profile-customer">
  <div class="container">
    <div class="row">
        <div class="col-lg-12"><h3>Thông tin tài khoản</h3></div>
        <div class="col-md-12">
            @if (session('order-delete'))
                <div class="alert alert-success">{{session('order-delete')}}</div> 
            @endif
        </div>
        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active" id="v-pills-account-tab" data-toggle="pill" href="#v-pills-account" role="tab" aria-controls="v-pills-account" aria-selected="true">Thông tin tài khoản</a>
              <a class="nav-link" id="v-pills-order-history-tab" data-toggle="pill" href="#v-pills-order-history" role="tab" aria-controls="v-pills-order-history" aria-selected="false">Lịch sử đặt hàng</a>
              <a href="{{route('logout-customer')}}" class="logout">Logout</a>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
                  <div class="card rounded shadow shadow-sm mr-bottom">
                    <div class="card-body">
                        <form class="form" action="{{route('profile.update', $customer->id)}}" role="form" autocomplete="off" id="formRegister" novalidate="" method="POST">
                            @csrf
                            <h2>Cập nhập thông tin</h2>
                            <hr>
                            @if(session('status'))
                                <div class="alert alert-success">{{session('status')}}</div>
                            @endif
                            <div class="form-group">
                              <label for="fullname">Họ và tên</label>
                            <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="fullname" name="fullname" value="{{$customer->name}}">
                              @error('fullname')
                                  <small class="text-danger">{{$message}}</small>
                              @enderror
                            </div>

                            <div class="form-group">
                                <label for="uname1">Email</label>
                                <input type="email" class="form-control form-control-lg rounded-0" name="email" id="uname1" required="" value="{{$customer->email}}" readonly>
                                <div class="invalid-feedback">Email không được bỏ trống.</div>
                            </div>

                            <div class="form-group">
                                <label>Mật khẩu</label>
                                <input type="password" class="form-control form-control-lg rounded-0" id="pwd1" required="" name="password" autocomplete="new-password">
                                @error('password')
                                  <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                          
                            <div class="form-group">
                                <label for="psw-repeat">Xác nhận mật khẩu</label>
                                <input type="password" class="change-bg form-control form-control-lg rounded-0"  id="psw-repeat" name="password_confirmation">
                                @error('password')
                                  <small class="text-danger">{{$message}}</small>
                                @enderror

                                @if (session('error'))
                                    <small class="text-danger">{{session('error')}}</small>
                                @endif
                            </div>

                            <div class="form-group">
                              <label for="address">Địa chỉ</label>
                               <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="address" name="address" value="{{$customer->address}}">
                              @error('address')
                               <small class="text-danger">{{$message}}</small>
                              @enderror
                            </div>
                          
                            <div class="form-group">
                              <label for="phone">Số điện thoại</label>
                              <input type="text" class="change-bg form-control form-control-lg rounded-0"  id="phone" name="phone" value="{{$customer->phone}}">
                              @error('phone')
                                <small class="text-danger">{{$message}}</small>
                              @enderror
                            </div>
                            <button type="submit" class="btn btn-success btn-lg " id="btnLogin">Cập nhật</button>
                        </form>
                    </div>
                    <!--/card-block-->
                  </div>
                </div>
                <div class="tab-pane fade" id="v-pills-order-history" role="tabpanel" aria-labelledby="v-pills-order-history-tab">
                    <div class="order-history-mobile">
                        <ul>
                         
                            @if (count($order_history) > 0)
                              @foreach ($order_history as $item)
                                  @foreach ($item as $v)
                                      @php
                                        $total_price=$v->qty*$v->price;
                                        $date = $v->date_order;
                                        $new_date = date(strtotime('3 hours', strtotime($date)));
                                        $date_now = strtotime("now");
                                      @endphp
                                      <div class="order-history-item">
                                          <li class="product-name-mobile">{{$v->product_name.'('.'Số lượng:'.$v->qty.', Màu:'.$v->color_name.', Size:'.$v->size_name.')'}}</li>
                                          <li>Mã đơn hàng: {{$v->order_code}}</li>
                                          <li>Đơn giá: <b>{{number_format($v->price,0, '', '.')}}đ</b></li>
                                          <li>Ngày đặt hàng: {{$v->date_order}}</li>
                                          <li>
                                            Trạng thái:
                                            @php
                                              switch ($v->status_order) {
                                                case '1':
                                                  echo 'Đã xử lý';
                                                  break;
                                                case '2':
                                                  echo 'Đã hủy đơn';
                                                  break;
                                                default:
                                                  echo 'Đang xử lý';
                                                  break;
                                              }    
                                            @endphp
                                          </li>
                                          <li>
                                              <span class="btn btn-danger {{($date_now > $new_date || $v->status_order == '1') ? 'disabled-refund' : ''}}">
                                                  <a href="{{route('order.history', [$v->order_id, $v->prd_id])}}" class="" onclick="return confirm('Bạn có muốn xóa sản phẩm này không?')">Hủy</a>
                                              </span>
                                          </li>
                                          <li>Tổng tiền: <span><b>{{number_format($total_price, 0, '', '.') }}đ</b></span></li>
                                      </div>
                                  @endforeach
                              @endforeach
                         
                          @endif

                          @if (count($order_histories) > 0)
                           <div class="order-history-multi">
                              @php          
                              $total_price = [];
                              @endphp 
                              @foreach ($order_histories as $item)
                                @foreach ($item as $order)
                                   @php
                                        
                                    $total_price[]=$order->qty*$order->price;
                                    $date = $order->date_order;
                                    $new_date = date(strtotime('3 hours', strtotime($date)));
                                    $date_now = strtotime("now");
                                    @endphp
                                    <div class="order-history-item-multi">
                                      <li class="product-name-mobile">{{$order->product_name.'('.'Số lượng:'.$order->qty.', Màu:'.$order->color_name.', Size:'.$order->size_name.')'}}</li>
                                      <li>Mã đơn hàng: {{$order->order_code}}</li>
                                      <li>Đơn giá: <b>{{number_format($order->price,0, '', '.')}}đ</b></li>
                                      <li>Ngày đặt hàng: {{$order->date_order}}</li>
                                      <li>
                                        Trạng thái:
                                        @php
                                          switch ($order->status_order) {
                                            case '1':
                                              echo 'Đã xử lý';
                                              break;
                                            case '2':
                                              echo 'Đã hủy đơn';
                                              break;
                                            default:
                                              echo 'Đang xử lý';
                                              break;
                                          }    
                                        @endphp
                                        
                                      </li>
                                      <li>
                                        <span class="btn btn-danger {{($date_now > $new_date || $order->status_order == '1') ? 'disabled-refund' : ''}}">
                                          <a href="{{route('order.history', [$order->order_id, $order->prd_id])}}" class="" onclick="return confirm('Bạn có muốn xóa sản phẩm này không?')">Hủy</a>
                                        </span>
                                      </li>
                                  </div>
                                @endforeach
                              @endforeach
                              <li class="order-history-item-multi"> <span>Tổng tiền: <b>{{number_format(array_sum($total_price), 0, '', '.') }}đ</b></span></li>
                            </div>
                          @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

@endsection