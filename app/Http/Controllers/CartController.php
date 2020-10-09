<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Customer;
use App\Http\Controllers\CustomerController;
use App\Mail\RegisterMail;
use App\Order;
use App\OrderDetail;
use App\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    function list(){
        $carts = Cart::content();
        foreach($carts as $item){
           foreach($item as $values){
           }
        }
        return view('cart.list');
    }

    function add(Request $request, $id){
        $request->validate([
            'product-color' => 'required',
        ],
        [
            'required' => ':attribute không được bỏ trống'
        ],
        [
            'product-color' => 'Màu sản phẩm',
            'product-size'  => 'Size'
        ]
        );

        if($request->input('qty')){
            $qty = $request->input('qty');
        }else{
            $qty = 1;
        }
        $color = $request->input('product-color');
        $size  = $request->input('product-size');

        $product = Product::find($id);
        $total_qty = 0;
        $product_info = DB::select("SELECT max(prd_attr.product_id) as product_id, max(prd_attr.size_id) as size_id, max(prd_attr.color_id) as color_id, max(prd_attr.amount) as amount FROM product_attribute as prd_attr INNER JOIN products on prd_attr.product_id = products.id  where prd_attr.product_id = $id and prd_attr.color_id =$color and prd_attr.size_id = $size GROUP by prd_attr.product_id");
        foreach($product_info as $item){
            $total_qty = $item->amount;
        }
        if(!empty($product->discount)){
            $product_price = (1 - ($product->discount/100))* $product->price;
        }else{
            $product_price = $product->price;
        }
        $cart =  Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'qty' => $qty,
            'price'    => $product_price,
            'options' => ['thumbnail' => $product->thumbnail, 'quantity'=>$product->quantity, 'color' => $color, 'size'=>$size , 'slug' => $product->slug, 'total' => $total_qty]
        ]);
        return redirect('cart')->with('cart_success', 'Bạn đã thêm sản phẩm vào giỏ hàng thành công');
    }

  
    function update(Request $request){
        $qty = $request->input('qty');
        $id = $request->input('id');
        Cart::update($id, ['qty' => $qty]);
        $data_price=  $request->input('dataPrice');
        $total = number_format($qty * $data_price, 0, '', '.').'đ';
        $sub_total =Cart::total().'đ';
        return response()->json(
                [
                    'id'    => $id,
                    'qty' => $qty,
                    'total' => $total,
                    'sub_total' => $sub_total
                ]
            );
    }

    function delete($rowId){
        $delete = Cart::remove($rowId);
        return redirect('cart');
    }

    function destroy(){
        Cart::destroy();
        return redirect('cart');
    }

    function pre_checkout(Request $request){
        if($request->session()->exists('login')){
           return redirect('checkout');
        }else{
           return redirect('dang-nhap');
        }
    }

    function checkout(){
        if(!empty(session('id')))
        $id = session('id');
        $customer = Customer::find( $id);
        return view('cart.checkout', compact('customer'));
    }

    function bill(Request $request){
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|max:255',
            'phone' => 'required |regex:/^[0]{1}[0-9]{9}$/',
            'note'  => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'address.max' => 'Độ dài tối đa của địa chỉ là 255',
            'note.max' => 'Độ dài tối đa của ghi chú là 255',
            'fullname.max' => 'Độ dài tối đa của họ tên là 255',
            'string' => 'Chuỗi',
            'regex' => 'Số điện thoại nhập không hợp lệ',
        ],
        [
            'fullname' => 'Họ và tên',
            'email' => 'Email',
            'address'  => 'Địa chỉ',
            'phone'    => 'Số điện thoại',
            'note' => 'Ghi chú'
        ]);
      
       $order_code = 'OD'.rand();
        
       $shipping = 0;
       if($request->input('shipping') == 'delivery'){
           $shipping = 30000;
       }

       $total = str_replace('.','',Cart::total());
       $sub_total = $total + (int) $shipping;

        $order = Order::create(
            [
                'order_code' => $order_code,
                'name' => $request->input('fullname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'note'  => $request->input('note'),
                'address' => $request->input('address'),
                'total' => $sub_total,
                'customer_id' => session('id'),
                'shipping' => $shipping,
                'status'   => '0',
                'payments'  => $request->input('payment-method'),
            ]
        );
        foreach(Cart::content() as $item){
           OrderDetail::create(
               [
                   'product_id' => $item->id,
                   'order_id'   => $order->id,
                   'qty'   => $item->qty,
                   'price'      => $item->price,
                   'color'      => $item->options['color'],
                   'size'       => $item->options['size'],
               ]
           );
           $color_id = $item->options['color'];
           $size_id = $item->options['size'];
           $product_id = $item->id;
           $product = DB::select("SELECT * FROM product_attribute INNER JOIN products on product_attribute.product_id = products.id 
           where product_attribute.product_id = $product_id  and product_attribute.size_id = $size_id and  product_attribute.color_id = $color_id");
           $product_qty = 0;
           foreach ($product as $key => $value) {
              $product_qty = $value->amount;
           }
           $quantity = (int)$product_qty - (int)$item->qty;
           ProductAttribute::whereIn('product_id' ,[$item->id])->where('size_id' , $size_id)->where('color_id', $color_id)->update(
               [
                   'amount'  => $quantity
               ]
           );
           $product_info = Product::whereIn('id', [$item->id])->get();
        }
        $order_info = DB::select("SELECT * FROM order_details INNER JOIN orders on order_details.order_id = orders.id INNER JOIN products on order_details.product_id  = products.id WHERE order_details.order_id = $order->id");
        $customer_info= Order::find($order->id);
        $cart_info = [
           'customer_info' =>  $customer_info, 
            'order_info'  => $order_info
        ];
        Mail::to($order->email)->send(new RegisterMail($cart_info));
        Cart::destroy();
        return redirect('dat-hang-thanh-cong.html');
    }

    function success(){
        $order = Order::where('status', '=', '0')->get();
        $list_id_order = [];
        foreach($order as $item){
            $list_id_order[] = explode(" ", $item->id);
        }
       $last_order_id = array_pop($list_id_order);
       $order_info = Order::find($last_order_id[0]);
       return view('cart.order_success', compact('order_info'));
    }
}
