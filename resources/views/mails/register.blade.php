<table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0" style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px" width="100%">
	<tbody>
		<tr>
			<td align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px" width="600">
				<tbody>
				
					<tr style="background:#fff">
						<td align="left" height="auto" style="padding:15px" width="600">
						<table>
							<tbody>
							
								<tr>
									<td>
									<h1 style="font-size:17px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Cảm ơn quý khách {{$cart_info['customer_info']->name}} đã đặt hàng tại <span class="il">Smart Shop</span>,</h1>
									
									<p style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><span class="il">Smart Shop</span> rất vui thông báo đơn hàng {{$cart_info['customer_info']->order_code}} của quý khách đã được tiếp nhận và đang trong quá trình xử lý. <span class="il">Smart Shop</span> sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.</p>
									
									<h3 style="font-size:13px;font-weight:bold;color:#02acea;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd">Thông tin đơn hàng {{$cart_info['customer_info']->order_code}}<span style="font-size:12px;color:#777;text-transform:none;font-weight:normal">	{{$cart_info['customer_info']->created_at}}</h3>
									</td>
								</tr>
								<tr>
									<td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th align="left" style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%">Thông tin thanh toán</th>
												<th align="left" style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold" width="50%"> Địa chỉ giao hàng </th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top"><span style="text-transform:capitalize">{{$cart_info['customer_info']->name}}</span><br>
												<a href="{{$cart_info['customer_info']->email}}" target="_blank">{{$cart_info['customer_info']->email}}</a><br>{{$cart_info['customer_info']->phone}}</td>
												<td style="padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal" valign="top"><span style="text-transform:capitalize">{{$cart_info['customer_info']->name}}</span><br>
												 <br>{{$cart_info['customer_info']->address}}<br>
												 SĐT: {{$cart_info['customer_info']->phone}}</td>
											</tr>
																						<tr>
												<td colspan="2" style="padding:7px 9px 0px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444" valign="top">
												<p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal"><strong>Phương thức thanh toán: </strong> @if ($cart_info['customer_info']->payments == 'payment-home')
													Thanh toán tiền mặt khi nhận hàng<br>
												@else
													Thanh toán qua paypal
												@endif 
												  <strong>Thời gian giao hàng dự kiến:</strong> @if ($cart_info['customer_info']->shipping == '30000')
													 Dự kiến giao hàng trong 24h tới - Không giao ngày chủ nhật <br>
												  @else
														@php
															$new_date=Date('m/d/Y', strtotime('+3 days'));
														@endphp
													  {{"Dự kiến giao vào ngày $new_date - không giao ngày Chủ Nhật"}} <br>
												  @endif 
												  <strong>Phí vận chuyển: </strong>{{number_format($cart_info['customer_info']->shipping, 0, '', '.')}}đ<br>
												 </p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
								
								<tr>
									<td>
									<h2 style="text-align:left;margin:10px 0;border-bottom:1px solid #ddd;padding-bottom:5px;font-size:13px;color:#02acea">CHI TIẾT ĐƠN HÀNG</h2>

									<table border="0" cellpadding="0" cellspacing="0" style="background:#f5f5f5" width="100%">
										<thead>
											<tr>
												<th align="left" bgcolor="#02acea" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Sản phẩm</th>
												<th align="left" bgcolor="#02acea" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Đơn giá</th>
												<th align="left" bgcolor="#02acea" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Số lượng</th>
												<th align="left" bgcolor="#02acea" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Giảm giá</th>
												<th align="right" bgcolor="#02acea" style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">Tổng tạm</th>
											</tr>
										</thead>
										<tbody bgcolor="#eee" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">	
											
											@foreach ($cart_info['order_info'] as $value)	
												
												<tr>
													<td align="left" style="padding:3px 9px" valign="top"><span>{{$value->title}}</span><br>
													</td>
													<td align="left" style="padding:3px 9px" valign="top"><span>
														{{number_format($value->price, 0, '', '.')}}đ
													</span></td>
													<td align="left" style="padding:3px 9px" valign="top">
														{{$value->qty}}
													</td>
													<td align="left" style="padding:3px 9px" valign="top">
														<span>
															{{$value->discount ? $value->discount . '%' : '0đ'}}
														</span>
													</td>
													<td align="right" style="padding:3px 9px" valign="top"><span>{{number_format($value->price * $value->qty,0 , '', '.')}}đ</span></td>
												</tr>
												
											@endforeach
										</tbody>
										<tfoot style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">											<tr>
												<td align="right" colspan="4" style="padding:5px 9px">Tạm tính</td>
												<td align="right" style="padding:5px 9px"><span>{{number_format($cart_info['customer_info']->total, 0 ,' ', '.')}}đ</span></td>
											</tr>
																						<tr>
												<td align="right" colspan="4" style="padding:5px 9px">Phí vận chuyển</td>
												<td align="right" style="padding:5px 9px"><span>{{number_format($cart_info['customer_info']->shipping, 0, '', '.')}}đ</span></td>
											</tr>
																						<tr bgcolor="#eee">
												<td align="right" colspan="4" style="padding:7px 9px"><strong><big>Tổng giá trị đơn hàng</big> </strong></td>
												<td align="right" style="padding:7px 9px"><strong><big><span>{{number_format($cart_info['customer_info']->total, 0 ,' ', '.')}}đ</span> </big> </strong></td>
											</tr>
										</tfoot>
									</table>

									{{-- <div style="margin:auto"><a href="https://tiki.vn/sales/order/trackingDetail?code=437966312" style="display:inline-block;text-decoration:none;background-color:#00b7f1!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:35%;margin-top:5px" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://tiki.vn/sales/order/trackingDetail?code%3D437966312&amp;source=gmail&amp;ust=1598112591290000&amp;usg=AFQjCNEV55spm510YW6G6eiDX12nJOeY6A">Chi tiết đơn hàng tại <span class="il">Tiki</span></a></div> --}}
									</td>
								</tr>
								
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>