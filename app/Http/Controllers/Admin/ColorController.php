<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Color;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ColorController extends Controller
{
    //
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
            $colors = Color::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $colors = Color::where('status', '=', '0')->paginate(10);
        }else{
            $colors = Color::where('status', '=', '1')->paginate(10);
        }
        $count_color_active = Color::all()->where('status','=', '1')->count();
        $count_color_trash = Color::onlyTrashed()->count();
        $count_color_pedding = Color::all()->where('status', '=', '0')->count();
        $count = [$count_color_active,$count_color_pedding, $count_color_trash];
        return view('admin.color.list', compact('colors', 'list_action', 'count'));
    }

    function add(){
        return view('admin.color.add');
    }

    function store(Request $request){
        $request->validate([
            'title' => 'required|unique:colors',
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'unique' => 'Tên màu đã tồn tại'
        ],
        [
            'title' =>'Tên màu'
        ]);
      
        Color::create(
            [
                'title' => $request->input('title'),
                'color_code' => $request->input('color'),
                'status' => $request->input('status'),
                'creator' => Auth::user()->name,
            ]);
        return redirect()->route('color.list')->with('status','Bạn đã thêm màu sản phẩm thành công');
    }

    function edit($id){
        $color = Color::find($id);
        return view('admin.color.edit', compact('color'));
    }

    function update(Request $request, $id){
        $request->validate([
            'title' => ['required',
            Rule::unique('colors')->ignore($id)],
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'unique' => 'Tên màu đã tồn tại'
        ],
        [
            'title' =>'Tên màu'
        ]);
        Color::where('id', $id)->update([
            'title' => $request->input('title'),
            'color_code' => $request->input('color'),
            'status' => $request->input('status'),
        ]);
      return redirect('admin/product/color/list')->with('status', 'Bạn đã cập nhập thành công');
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $colors= Color::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $colors= Color::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
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
                        if($colors->total() >0){
                            $count = 0;   
                            foreach ($colors as $color){
                                        $count ++;   
                            ?>
                                <tr data-id="<?php echo $color->id ?>" class="element">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="<?php echo $color->id; ?>">
                                    </td>
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php echo route('color.edit', $color->id)?>"><?php echo $color->title; ?></a></td>
                                    <td><?php echo $color->creator; ?></td>
                                    <td><?php echo $color->created_at; ?></td>
                                    <td>
                                        <?php 
                                            if(Gate::allows('product-color-edit')){
                                                ?>
                                                <a href="<?php echo route('color.edit', $color->id) ?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }

                                            if(Gate::allows('product-color-delete')){
                                                ?>
                                                <a href="<?php echo route('color.delete', $color->id) ?>" data-id="<?php echo $color->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
       echo $colors->links();
    }



    function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $colors = Color::where('status', '=', '1')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $colors = Color::where('status', '=', '0')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $colors = Color::where('status', '=', '1')->paginate(10);
                  }else{
                    $colors = Color::onlyTrashed()->paginate(10);
                 }
            }
            return view('admin.color.pagination',compact('colors'))->render();
        }
    }

    function delete($id){
        $color = Color::find($id);
        $color->delete();
        return response()->json(
            [
                'status' => 1,
                'id' => $id,
                'message' => '<div class=" alert alert-success">'. 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
                'color' => $color
            ]);
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $color =  Color::whereIn('id', $list_check)->update([
                 'status' => '1'
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'color' => $color
        ]);
        }else if($action == 'delete'){
            $color =  Color::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'color' => $color
            ]);
        }else if($action == 'restore'){
           
            $color =  Color::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'color' => $color
            ]);
        }else{
            $color =  Color::whereIn('id', $list_check);
            $color ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'color' => $color
            ]);
        }
     }
}
