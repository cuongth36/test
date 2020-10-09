<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Size;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request,$next){
            session(['module_active' => 'product']);
            return $next($request);
        });
    }

    function list(Request $request){
        $status = $request->input('status');
        $list_action =['delete' => 'Xóa tạm thời'];
        if($status == 'trash'){
            $list_action = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            $sizes = Size::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $sizes = Size::where('status', '=', '0')->paginate(10);
        }else{
            $sizes = Size::where('status', '=', '1')->paginate(10);
        }
        $count_size_active = Size::all()->where('status','=', '1')->count();
        $count_size_trash = Size::onlyTrashed()->count();
        $count_size_pedding = Size::all()->where('status', '=', '0')->count();
        $count = [$count_size_active,$count_size_pedding, $count_size_trash];
        return view('admin.size.list', compact('sizes', 'list_action', 'count'));
    }

    function add(){
        return view('admin.size.add');
    }

    function store(Request $request){
        $request->validate([
            'name' => 'required|unique:sizes',
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'unique' => 'Tên size đã tồn tại'
        ],
        ['name'=> 'Tên size']
    
    );
   
        Size::create(
            [
                'name' => $request->input('name'),
                'status' => trim($request->input('status')),
                'creator' => Auth::user()->name,
            ]);
        return redirect()->route('size.list')->with('status','Bạn đã thêm màu sản phẩm thành công');
    }

    function edit($id){
        $size = Size::find($id);
        return view('admin.size.edit', compact('size'));
    }

    function update(Request $request, $id){
        
        $request->validate([
            'name' => ['required',
            Rule::unique('sizes')->ignore($id)],
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'unique' => 'Tên size đã tồn tại'
        ]);
        Size::where('id', $id)->update([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ]);
      return redirect('admin/product/size/list')->with('status', 'Bạn đã cập nhập thành công');
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $sizes= Size::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $sizes= Size::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }?>
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
                        <th scope="col">Tên màu</th>
                        <th scope="col">Người tạo</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </thead>
                    <tbody>
                        <?php
                        if($sizes->total() >0){
                            $count = 0;   
                            foreach ($sizes as $size){
                                        $count ++;   
                            ?>
                                <tr data-id="<?php echo $size->id ?>" class="element">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="<?php echo $size->id; ?>">
                                    </td>
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php echo route('size.edit', $size->id)?>"><?php echo $size->name; ?></a></td>
                                    <td><?php echo $size->creator; ?></td>
                                    <td><?php echo $size->created_at; ?></td>
                                    <td>
                                        <?php
                                                
                                                if(Gate::allows('product-size-edit')){
                                                ?>
                                                    <a href="<?php echo route('size.edit', $size->id) ?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                                <?php
                                                    }
                                                    if(Gate::allows('product-size-delete')){
                                                        ?>
                                                        <a href="<?php echo route('size.delete', $size->id) ?>" data-id="<?php echo $size->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
       echo $sizes->links();
    }

    function delete($id){
        $size = Size::find($id);
        $size->delete();
        return response()->json(
            [
                'status' => 1,
                'id' => $id,
                'message' => '<div class=" alert alert-success">'. 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
                'size' => $size
            ]);
    }

    function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $sizes = Size::where('status', '=', '1')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $sizes = Size::where('status', '=', '0')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $sizes = Size::where('status', '=', '1')->paginate(10);
                  }else{
                    $sizes = Size::onlyTrashed()->paginate(1);
                 }
            }
            return view('admin.size.pagination',compact('sizes'))->render();
        }
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $size =  Size::whereIn('id', $list_check)->update([
                 'status' => '1'
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'size' => $size
        ]);
        }else if($action == 'delete'){
            $size =  Size::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'size' => $size
            ]);
        }else if($action == 'restore'){
           
            $size =  Size::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'size' => $size
            ]);
        }else{
            $size =  Size::whereIn('id', $list_check);
            $size ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'size' => $size
            ]);
        }
     }
}
