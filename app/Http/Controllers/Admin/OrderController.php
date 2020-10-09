<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function list(Request $request){
        $status = $request->input('status');
        $list_action =[];
        
        $count_order_active =DB::table('orders')->where('status','=', '1')->count();
        $count_order_trash = DB::table('orders')->where('status','=', '2')->count();
        $count_order_pedding = DB::table('orders')->where('status', '=', '0')->count();
        $count = [$count_order_active,$count_order_pedding, $count_order_trash];
        $num_per_page = 10;
        $num_page_active = ceil($count_order_active/$num_per_page);
        $num_page_pedding = ceil($count_order_pedding/$num_per_page);
        $num_page_trash = ceil($count_order_trash/$num_per_page); 
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset =  ($page - 1)*$num_per_page ;
        $num_page = [$num_page_active, $num_page_pedding,$num_page_trash];
        if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa vĩnh viễn',
            ];
                $orders =  DB::select(
                    "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '0' GROUP BY (a.id) LIMIT {$num_per_page} OFFSET {$offset} "
                );
                
        }
        else if($status == 'trash'){
                $orders =  DB::select(
                "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '2' GROUP BY (a.id) LIMIT $num_per_page OFFSET {$offset}"
                );
        }else{
                $orders =  DB::select(
                "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '1' GROUP BY (a.id) LIMIT $num_per_page OFFSET {$offset}"
                );  
        }
       
        return view('admin.order.list', compact('orders', 'list_action', 'count', 'num_page'));
        
    }


    function edit($id){
        $order =DB::select("SELECT od.*,odt.qty, prd.title, prd.price from order_details odt inner join orders od on odt.order_id = od.id
                            inner join products prd on odt.product_id = prd.id where od.id = {$id}");
        return view('admin.order.edit',compact('order'));
    }

    function update(Request $request, $id){
        DB::table('orders')
              ->where('id', $id)
              ->update(['status' =>  $request->input('update-status')]);
       
        return redirect('admin/order/list')->with('status','Cập nhật đơn hàng thành công');
    }

    function delete($id){
        $order =  DB::table('orders')->where('id', '=', $id)->delete();
        return response()->json(
            [
                'status' => 1,
                'id' => $id,
                'message' => '<div class=" alert alert-success">'. 'Bạn đã xóa đơn hàng thành công'. '</div>',
                'order' => $order
            ]);
    }

    function pagination(Request $request){
        if($request->ajax()){
            $count_order_active =DB::table('orders')->where('status','=', '1')->count();
            $count_order_trash = DB::table('orders')->where('status','=', '2')->count();
            $count_order_pedding = DB::table('orders')->where('status', '=', '0')->count();
            $count = [$count_order_active,$count_order_pedding, $count_order_trash];
            $num_per_page = 10;
            $num_page_active = ceil($count_order_active/$num_per_page);
            $num_page_pedding = ceil($count_order_pedding/$num_per_page);
            $num_page_trash = ceil($count_order_trash/$num_per_page); 
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset =  ($page - 1)*$num_per_page ;
            $num_page = [$num_page_active, $num_page_pedding,$num_page_trash];

            if(!$request['status']){
                $orders =  DB::select(
                    "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '1' GROUP BY (a.id) LIMIT $num_per_page OFFSET {$offset}"
                    );  
            }else{
                if ($request['status'] == 'pendding'){
                    $list_action = [
                        'approve' => 'Phê duyệt',
                        'delete' => 'Xóa vĩnh viễn',
                    ];
                        $orders =  DB::select(
                            "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '0' GROUP BY (a.id) LIMIT {$num_per_page} OFFSET {$offset} "
                        );
                        
                }
                else if($request['status'] == 'trash'){
                        $orders =  DB::select(
                        "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '2' GROUP BY (a.id) LIMIT $num_per_page OFFSET {$offset}"
                        );
                }else{
                        $orders =  DB::select(
                        "SELECT max(b.price) as price, SUM(b.qty) as qty,max(a.order_code) as order_code, max(a.name) as name, max(a.phone) as phone, max(a.total) as total, max(a.created_at) as created_at, max(a.status) as status, max(a.id) as id from orders a LEFT JOIN order_details b on a.id = b.order_id where a.status = '1' GROUP BY (a.id) LIMIT $num_per_page OFFSET {$offset}"
                        );  
                }
            }
            return view('admin.order.pagination',compact('orders', 'num_page'))->render();
        }
    }


    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $orders = DB::select("SELECT od.*, odt.qty from orders od inner join order_details odt on od.id = odt.order_id where od.name LIKE '%$keyword%'");
        }else{
            $orders = DB::select("SELECT od.*, odt.qty from orders od inner join order_details odt on od.id = odt.order_id where od.name LIKE '%$keyword%'");
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
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">STT</th>
                        <th scope="col">Mã</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Giá trị</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </thead>
                    <tbody>
                        <?php
                        if(count($orders) >0){
                            $count = 0;   
                          
                            foreach ($orders as $order){
                                        $count ++;  
                            ?>
                                <tr data-id="<?php echo $order->id ?>" class="element">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="<?php echo $order->id; ?>">
                                    </td>
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><?php echo $order->order_code; ?></td>
                                    <td><?php echo $order->name . '<br>'. $order->phone;?></td>
                                    <td><?php echo $order->qty; ?></td>
                                    <td><?php echo number_format($order->total,0,'', '.');?>đ</td>
                                    <td>
                                        <span class="badge badge-warning"> <?php 
                                         if($order->status == '1') 
                                          echo 'Hoàn thành';
                                         else if($order->status == '0') 
                                         echo 'Đang xử lý';
                                         else
                                         echo 'Hủy'
                                        ?></span>
                                    </td>
                                    <td><?php echo $order->created_at; ?></td>
                                    <td>
                                        <?php 
                                            if(Gate::allows('order-edit')){
                                                ?>
                                                <a href="<?php echo route('order.edit', $order->id) ?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }

                                            if(Gate::allows('order-delete')){
                                                ?>
                                                 <a href="<?php echo route('order.delete', $order->id) ?>" data-id="<?php echo $order->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            <?php
                                            }
                                        ?>
                                    
                                    </td>
            
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
    //    echo $orders->links();
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $order =  Order::whereIn('id', $list_check)->update([
                 'status' => '1'
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'order' => $order
        ]);
        }else {
            $order =  Order::whereIn('id', $list_check);
            $order->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi thành công' . '</div>', 
                'order' => $order
            ]);
        }
     }

     
}
