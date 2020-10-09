<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'customer']);
            return $next($request);
        });

    }

    function list(){
        $customers = Customer::where('is_active', '=', '1')->paginate(10);
        return view('admin.customer.list', compact('customers'));
    }

    function pagination(Request $request){
        if($request->ajax()){
            $customers = Customer::where('is_active', '=', '1')->paginate(10);
            return view('admin.customer.pagination',compact('customers'))->render();
        }
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $customers= Customer::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
            
        }else{
            $customers= Customer::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        return view('admin.customer.pagination', compact('customers'));
    }
}
