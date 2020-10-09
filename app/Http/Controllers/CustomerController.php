<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Customer;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegisterCustomer;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\OrderDetail;

class CustomerController extends Controller
{
    function __construct()
    {
       
    }

    function login(){
        return view('customer.login');
    }

    function register(){
        return view('customer.register');
    }

    function signin(){
        return view('customer.form-login');
    }

    function loginCustomer(Request $request){
        if(session()->exists('login')){
            $id = session('id');
            return redirect("khach-hang/thong-tin/{$id}");
         }else{
            return view('customer.form-login');
            // return redirect('sign-in');
         }
    }

    function profile(Request $request){
        
            $id = session('id');
            $count_id = 0;
            $db_select = DB::select("SELECT count('id') as id FROM customers where id = $id ");
            foreach ($db_select as $item){
               $count_id = $item->id;
            }
         
            if($count_id > 0){
                $customer = Customer::find($id);
                $customer_id = session('id');
              
                $order_id = DB::select("SELECT orders.id FROM orders WHERE orders.customer_id = $id");
                $order_histories = [];
                $order_history = [];
                if(!empty($order_id)){
                    foreach($order_id as $item){
                        $product_by_order = DB::select("SELECT ord.price, ord.order_id, ord.product_id as prd_id, ord.qty, orders.id as id_order, orders.order_code, sizes.name as size_name, colors.title as color_name,orders.status as status_order , products.title as product_name ,orders.created_at as date_order FROM order_details ord INNER JOIN products 
                        on ord.product_id = products.id 
                        INNER JOIN sizes on ord.size = sizes.id
                        INNER JOIN orders on ord.order_id = orders.id
                        INNER JOIN colors on ord.color = colors.id WHERE ord.order_id =$item->id ORDER BY ord.order_id DESC ");
                        if(count($product_by_order) > 1){
                            $order_histories[] = $product_by_order;
                        }else{
                            $order_history[] = $product_by_order;
                        }
                    }
                }

                return view('customer.profile', compact('customer', 'order_history', 'order_histories'));
    
            }else{
                return redirect('404.html');
            }
    }

    //Check register account
    function checkSignin(Request $request){
        $user = Customer::where('email', '=',  $request->input('email'))->where('is_active', '=', '1')->first();
        if($user){
            $customer_info = Hash::check($request->input('password'), $user->password);
            if($customer_info){
                session([
                    'login' => true,
                    'email' => $request->input('email'),
                    'id'    => $user->id,
                    'name'  => $user->name,
                ]);
                return redirect("khach-hang/thong-tin/{$user->id}");
            }
            return redirect('dang-nhap.html')->with('error','Email hoặc mật khẩu không tồn tại trong hệ thống');
        }else{
            return redirect('dang-nhap.html')->with('error','Email hoặc mật khẩu không tồn tại trong hệ thống');
        }
    }


    //Check register account checkout
    public function checkLogin( Request $request){
        $user = Customer::where('email', '=',  $request->input('email'))->where('is_active', '=', '1')->first();
        if($user){
            $customer_info = Hash::check($request->input('password'), $user->password);
            if($customer_info){
                session([
                    'login' => true,
                    'email' => $request->input('email'),
                    'id'    => $user->id,
                    'name'  => $user->name,
                ]);
                return redirect('checkout');
            }
            return redirect('dang-nhap')->with('error','Email hoặc mật khẩu không tồn tại trong hệ thống');
        }else{
            return redirect('dang-nhap')->with('error','Email hoặc mật khẩu không tồn tại trong hệ thống');
        }
    }

   
    function store(Request $request){
      
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => '|unique:customers|required|email',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'address' => 'max:255',
            'phone' => 'required |regex:/^[0]{1}[0-9]{9}$/',
            // 'remember' => 'required'
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'fullname.max' => 'Độ dài tối đa của tên là 255 ký tự ',
            'address.max' => 'Độ dài tối đa của địa chỉ là 255 ký tự ',
            'string' => 'Chuỗi',
            'unique' => 'Email đã tồn tại',
            'confirmed' => 'Mật khẩu không khớp',
            'min' => 'Độ dài tối thiếu của mật khẩu là 6 ký tự',
            'regex' => 'Số điện thoại không đúng định dạng'
        ],
        [
            'fullname' => 'Họ và tên',
            'password' => 'Mật khẩu',
            'email' => 'Email',
            'address'  => 'Địa chỉ',
            'phone'    => 'Số điện thoại',
            'remember' => 'Điều khoản'
        ]);
        
        $active_token = md5($request->input('email').time());
        $customer =  Customer::create([
            'name'     =>   trim($request->input('fullname')),
            'email'    => trim($request->input('email')),
            'password' => trim(Hash::make($request->input('password'))),
            'address'  => trim($request->input('address')),
            'phone'    => trim($request->input('phone')),
            'is_active' => '0',
            'active_token' => $active_token,
        ]);
       
        $link_active = route('active.token', "active-token=$active_token");
        Mail::to($customer->email)->send(new RegisterCustomer($link_active));
       
        return redirect('dang-ky.html')->with('status', 'Bạn vui lòng kiểm tra email để kích hoạt tài khoản');
    }

    function activeToken(Request $request){
        $active_token =$request->input('active-token');
        $num_active_token =Customer::where('active_token', '=' , "$active_token")->where('is_active', '=', '0')->count();
        if($num_active_token > 0){
            Customer::where('active_token', '=' , "$active_token")->where('is_active', '=', '0')->update([
                'is_active' => '1'
            ]);
            return redirect('kich-hoat-thanh-cong.html');
        }else{
            return redirect('404.html');
        }
    }

    function activeSuccess(){
        return view('mails.active-success');
    }

    function forgotPassword(){
        return view('customer.forgot-pass');
    }

    function checkEmailForgot(Request $request){
       $email_forgot = $request->input('email_forgot');
       $customer_info = Customer::where('email', '=', "$email_forgot")->count();
       $password_token = md5($email_forgot.time());
       $confirm_pass=  route('customer.view-confirm', "confirm=$password_token");
       if($customer_info > 0){
            Customer::where('email', '=', "$email_forgot")->update([
                'password_token' => $password_token
            ]);
            Mail::to($email_forgot)->send(new ForgotPassword($confirm_pass));
            return redirect('khoi-phuc-mat-khau.html')->with('email_success', 'Bạn vui lòng truy cập email để thay đổi mật khẩu');
       }else{
            return redirect('khoi-phuc-mat-khau.html')->with('email_fail', 'Email không tồn tại trong hệ thống');
       }
    }

    function viewForgotPassword(Request $request){
        $confirm_pass = $request->input('confirm');
        return view('customer.confirm-password', compact('confirm_pass'));
    }

    function forgotConfirm(Request $request){
        $confirm_password = $request->input('confirm');
        $password_new = $request->input('password');
        $num_password_token = Customer::where('password_token', '=', "$confirm_password")->count();
        if($num_password_token > 0){
            
            $request->validate([  
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ],
            [
                'required' => ':attribute không được bỏ trống',
                'unique' => 'Email đã tồn tại',
                'confirmed' => 'Mật khẩu không khớp',
                'min' => 'Độ dài tối thiếu của mật khẩu là 6 ký tự',
            ],
            [
                'password' => 'Mật khẩu',
            ]);
          
            Customer::where('password_token', '=', "$confirm_password")->update([
                'password' => trim(Hash::make($password_new))
            ]);
            return redirect('khoi-phuc-mau-khau/xac-nhan-mat-khau.html')->with('confirm_success', 'Bạn đã thay đổi mật khẩu thành công');
        }else{
            return redirect('404.html');
        }
       
    }

    function update(Request $request){
        $arg_rules = [
            'fullname' => 'required|string|max:255',
            'address' => 'max:255',
            'password' => 'required|string|min:6|', 
            'phone' => 'required |regex:/^[0]{1}[0-9]{9}$/',
        ];
        $arg_message = [
            'required' => ':attribute không được bỏ trống',
            'password.max' => 'Độ dài tối đa cuả mật khẩu là 40 ký tự',
            'fullname.max' => 'Độ dài tối đa cuả họ và tên là 255 ký tự',
            'address.max' => 'Độ dài tối đa cuả địa chỉ là 40 ký tự',
            'password.min' =>'Độ dài tối thiểu của mật khẩu là 6 ký tự',
            'string' => 'Chuỗi',
            'regex' => 'Số điện thoại không đúng định dạng'
        ];
        $id = session('id');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirmation');
       
        if(!empty($password_confirm) !== !empty($password)){

            return redirect("khach-hang/thong-tin/{$id}")->with('error', 'Bạn cần nhập mật khẩu trước khi cập nhật');
        }
    
        Validator::make($request->all(),$arg_rules, $arg_message)->validate();
        Customer::find($id)->where('id', $id)->update([
            'name' => trim($request->input('fullname')),
            'address'  => trim($request->input('address')),
            'phone'    => trim($request->input('phone')),
            'password' => trim(Hash::make($password))      
        ]);

        return redirect("khach-hang/thong-tin/{$id}")->with('status', 'Bạn đã cập nhập thông tin thành công');
        
    }

    function logout(Request $request){
        $request->session()->forget('login');
        return redirect()->route('homepage');
    }

    function updateOrderHistory(Request $request){
       $customer_id = session('id');
       $order_id = $request->segment(2);
       $product_id = $request->segment(3);
       $order =DB::select("SELECT COUNT(ord.product_id) as total_product, max(ord.order_id) FROM order_details as ord INNER JOIN orders on ord.order_id = orders.id where ord.order_id= $order_id group by ord.order_id");
        if($order[0]->total_product == 1){
            Order::where('id', '=', $order_id)->update([
                'status' => '2'
            ]);
        }
       OrderDetail::where('order_id', '=', $order_id)->where('product_id', '=', $product_id)->delete();
        
       return redirect("khach-hang/thong-tin/{$customer_id}")->with('order-delete', 'Bạn đã xóa sản phẩm thành công');
   
    }

   
}
