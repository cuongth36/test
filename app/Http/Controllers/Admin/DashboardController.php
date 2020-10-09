<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }

    function show(){ 
        $count_order =DB::table('orders')->count();
        $num_per_page = 10;
        $num_page = ceil($count_order/$num_per_page);
       
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset =  ($page - 1)*$num_per_page ;
        $num_order_success = DB::table('orders')->where('orders.status', '=', '1')->count();
        $num_order_pendding = DB::table('orders')->where('orders.status', '=', '0')->count();
        $num_order_cancel = DB::table('orders')->where('orders.status', '=', '2')->count();
        $info_order = [$num_order_success, $num_order_pendding, $num_order_cancel];
        $total_inventory = 0;
        
        $total_order = DB::table('orders')->where('orders.status', '=', '1')->get();
        foreach($total_order as $item){
            $total_inventory += $item->total;
        }
        $orders =  DB::select("SELECT max(b.price) as price,SUM(b.qty) as qty ,max(a.order_code) as order_code, max(a.id) as id, max(a.status) as status,  max(a.name) as name, max(a.created_at) as created_at, max(a.phone) as phone from orders a inner join order_details b on a.id = b.order_id GROUP BY (a.id) ORDER BY a.created_at DESC LIMIT $num_per_page OFFSET {$offset}"); 
        // dd($orders);
        return view('admin.dashboard', compact('orders', 'num_page', 'info_order', 'total_inventory'));
        // return view('admin.dashboard', compact( 'num_page', 'info_order', 'total_inventory'));
    }
}
