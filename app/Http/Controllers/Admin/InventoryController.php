<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\OrderDetail;
use App\Order;
use DateTime;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function list(){
        $count_inventory =DB::select("SELECT max(a.title) as title , a.id, sum( a.qty) as qty, sum(a.amount) as amount , a.size, a.color, MAX(a.color_name) as color_name , MAX(a.size_name) as size_name FROM

        (SELECT MAX(a.title) as title , a.id, SUM(b.qty) as qty, 0 as amount , b.size, b.color, '' as color_name , '' as size_name
                 FROM `products` a left JOIN order_details b on a.id = b.product_id 
             where b.size != 0 and b.color != 0 
            GROUP BY b.size, b.color, a.id
            UNION ALL
            SELECT MAX(a.title) as title , a.id, 0 as qty, SUM(b.amount) as amount, b.size_id as size, b.color_id as color, MAX(cl.title) as color_name , MAX(sz.name) as size_name
                 FROM `products` a left JOIN product_attribute b on a.id = b.product_id LEFT JOIN colors cl on b.size_id = cl.id
         		LEFT JOIN sizes sz on b.size_id = sz.id
         where b.size_id != 0 and b.color_id != 0
              GROUP BY b.size_id, b.color_id, a.id
           ) a
           GROUP by  a.size, a.color, a.id");
        $num_per_page = 10;
        $product_info = Product::all();
        $num_inventory = ceil(count($count_inventory)/$num_per_page);
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset =  ($page - 1)*$num_per_page ;
        $inventory = DB::select("SELECT max(a.title) as title , a.id, sum( a.qty) as qty, sum(a.amount) as amount , a.size, a.color, MAX(a.color_name) as color_name , MAX(a.size_name) as size_name FROM

        (SELECT MAX(a.title) as title , a.id, SUM(b.qty) as qty, 0 as amount , b.size, b.color, '' as color_name , '' as size_name
                 FROM `products` a left JOIN order_details b on a.id = b.product_id 
             where b.size != 0 and b.color != 0 
            GROUP BY b.size, b.color, a.id
            UNION ALL
            SELECT MAX(a.title) as title , a.id, 0 as qty, SUM(b.amount) as amount, b.size_id as size, b.color_id as color, MAX(cl.title) as color_name , MAX(sz.name) as size_name
                 FROM `products` a left JOIN product_attribute b on a.id = b.product_id LEFT JOIN colors cl on b.size_id = cl.id
         		LEFT JOIN sizes sz on b.size_id = sz.id
         where b.size_id != 0 and b.color_id != 0
              GROUP BY b.size_id, b.color_id, a.id
           ) a
           GROUP by  a.size, a.color, a.id order by a.title limit $num_per_page offset $offset");
        return view('admin.order.inventory',compact('inventory', 'num_inventory'));
    }

   

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $inventory = DB::table('products')
                        ->select(DB::raw('products.*, sum(order_details.qty) as qty'))
                        ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')->where('products.title', 'LIKE', "%$keyword%")->groupBy('products.id')->get();
        }else{
            $inventory = DB::table('products')
                        ->select(DB::raw('products.*, sum(order_details.qty) as qty'))
                        ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')->where('products.title', 'LIKE', "%$keyword%")->groupBy('products.id')->get();
        }
        ?>
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
                                <th scope="col">Số lượng nhập</th>
                                <th scope="col">Số lượng bán</th>
                                <th scope="col">Số lượng còn</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($inventory) >0){
                            $count = 0;   
                          
                            foreach ($inventory as $item){
                                        $count ++;  
                                        $qty_remaining = (int)$item->quantity  - (int)$item->qty;
                            ?>
                                <tr class="element">
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><?php echo $item->title; ?></td>
                                    <td><?php echo $item->quantity;?></td>
                                    <td><?php echo !empty($item->qty) ? $item->qty : 0 ; ?></td>
                                    <td><?php echo $qty_remaining ?></td>
                                </tr>
                                <?php
                            }
                        }else{
                            ?>
                             <tr>
                                <td class="bg-white" colspan="4">Không tìm thấy bản ghi</td>
                            </tr>
                        <?php
                        }
                        
                        ?>
                        
                    </tbody>
        </table>
        <?php
    }

    function revenueStatistics(Request $request){
        $start_date = strtotime(date('Y-m-01'));
        $end_date = strtotime(date('Y-m-t'));

        if($request->has('start-date')){
           $start_date = strtotime($request->input('start-date'));
        }

        if($request->has('end-date')){
            $end_date = strtotime($request->input('end-date'));
        }

        $new_start_date = date('Y-m-d', $start_date);
        $new_end_date = date('Y-m-d', $end_date);
        $total_price = DB::table('orders')->select(DB::raw('orders.total, orders.*'))->whereRaw(" orders.created_at BETWEEN '$new_start_date' AND '$new_end_date' AND orders.status = '1' ")->groupBy('orders.id')->get();
        return view('admin.order.revenue', compact('total_price' , 'start_date', 'end_date'));
    }

    function getData(Request $request){
        $start_date = strtotime(date('Y-m-01'));
        $end_date = strtotime(date('Y-m-t'));
      
        if($request->input('startDate')){
            $start_date = strtotime($request->input('startDate'));
            $date_create = date_create($request->input('startDate'));
            $new_start_date = date_format($date_create,'Y-m-d');
        }else{
            $new_start_date = date('Y-m-d', $start_date);
        }

        if($request->input('endDate')){
            $end_date = strtotime($request->input('endDate'));
            $date_create = date_create($request->input('endDate'));
            $new_end_date = date_format($date_create,'Y-m-d');

        }else{
            
            $new_end_date = date('Y-m-d', $end_date);
        }
        $total_price = DB::table('orders')->select(DB::raw('orders.total, orders.*'))->whereRaw(" orders.created_at BETWEEN '$new_start_date' AND '$new_end_date' AND orders.status = '1' ")->groupBy('orders.id')->get();
      
        // duyet tung ngay trong thang de lay data
        $total = 0;
        $data_total = []; 
        $data_label = [];
        for ($i= $start_date; $i <= $end_date; $i = strtotime('+1 day', $i)){
            if(!empty($total_price)){
                foreach($total_price as $item){
                    $date = date_create($item->created_at);
                    $date_formart = date_format($date, 'Y-m-d');
                    if(date('Y-m-d',$i) == $date_formart){
                        
                        $data_total[] = $item->total;
                        $total += $item->total;
                    }
                }
            }
            $data_label[]= date('Y-m-d',$i); 
        }
        $create_start_date = date_create($new_start_date);
        $create_end_date = date_create($new_end_date);
         return response()->json(
                [
                    'status' => 1,
                    'data_total' => $data_total,
                    'data_label' => $data_label,
                    'total' =>number_format($total, 0 , '' , '.'),
                    'startDate' => date_format($create_start_date, 'm/d/Y'),
                    'endDate' =>   date_format($create_end_date, 'm/d/Y'),
                ]
        );
    }

    function pagination(){
        $count_inventory =DB::select("SELECT max(a.title) as title , a.id, sum( a.qty) as qty, sum(a.amount) as amount , a.size, a.color, MAX(a.color_name) as color_name , MAX(a.size_name) as size_name FROM

        (SELECT MAX(a.title) as title , a.id, SUM(b.qty) as qty, 0 as amount , b.size, b.color, '' as color_name , '' as size_name
                 FROM `products` a left JOIN order_details b on a.id = b.product_id 
             where b.size != 0 and b.color != 0 
            GROUP BY b.size, b.color, a.id
            UNION ALL
            SELECT MAX(a.title) as title , a.id, 0 as qty, SUM(b.amount) as amount, b.size_id as size, b.color_id as color, MAX(cl.title) as color_name , MAX(sz.name) as size_name
                 FROM `products` a left JOIN product_attribute b on a.id = b.product_id LEFT JOIN colors cl on b.size_id = cl.id
         		LEFT JOIN sizes sz on b.size_id = sz.id
         where b.size_id != 0 and b.color_id != 0
              GROUP BY b.size_id, b.color_id, a.id
           ) a
           GROUP by  a.size, a.color, a.id");
        $num_per_page = 10;
        $num_inventory = ceil(count($count_inventory)/$num_per_page);
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset =  ($page - 1)*$num_per_page ;
        $inventory =  DB::select("SELECT max(a.title) as title , a.id, sum( a.qty) as qty, sum(a.amount) as amount , a.size, a.color, MAX(a.color_name) as color_name , MAX(a.size_name) as size_name FROM

        (SELECT MAX(a.title) as title , a.id, SUM(b.qty) as qty, 0 as amount , b.size, b.color, '' as color_name , '' as size_name
                 FROM `products` a left JOIN order_details b on a.id = b.product_id 
             where b.size != 0 and b.color != 0 
            GROUP BY b.size, b.color, a.id
            UNION ALL
            SELECT MAX(a.title) as title , a.id, 0 as qty, SUM(b.amount) as amount, b.size_id as size, b.color_id as color, MAX(cl.title) as color_name , MAX(sz.name) as size_name
                 FROM `products` a left JOIN product_attribute b on a.id = b.product_id LEFT JOIN colors cl on b.size_id = cl.id
         		LEFT JOIN sizes sz on b.size_id = sz.id
         where b.size_id != 0 and b.color_id != 0
              GROUP BY b.size_id, b.color_id, a.id
           ) a
           GROUP by  a.size, a.color, a.id order by a.title limit $num_per_page offset $offset");
        return view('admin.order.inventory-pagination',compact('inventory', 'num_inventory'));
    }
    
}
