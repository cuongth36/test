<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request,$next){
            session(['module_active' => 'user']);
            return $next($request);
        });
    }

    function list(){
        $roles = Role::orderBy('name', 'asc')->paginate(10);
        return view('admin.role.list', compact('roles'));
    }

    function add(){
       return view('admin.role.add');
    }

    function store(Request $request){
        $request->validate([
            'name' => 'required|max:255|unique:roles',
            'description' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên quyền đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'name' => 'Tên quyền',
            'description' => 'Mô tả'
        ]);
        
        Role::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'key_code' => Str::slug($request->input('name'), '-'),
        ]);
        return redirect('admin/role/list')->with('status', 'Thêm quyền thành công');
    }

    function edit($id){
        $roles = Role::find($id);
        return view('admin.role.edit', compact('roles'));
    }

    function update(Request $request, $id){
        $request->validate(
        [
            'name' =>   [
                'required',
                'max:255',
                Rule::unique('roles')->ignore($id),
            ],
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên quyền đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'name' => 'Tên quyền',
            'description' => 'Mô tả'
        ]);
       
        Role::where('id', $id)->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'key_code' => Str::slug($request->input('name'), '-'),
        ]);
       
      return redirect('admin/role/list')->with('status', 'Bạn đã cập nhập thành công');
    }

    function delete($id){
        $role = Role::find($id);
            $role->delete();
            return response()->json([
                    'status' => 1,
                    'id' => $id,
                    'message' => '<div class="alert alert-success">' . 'Bạn đã xóa bản ghi  thành công'. '</div>',
                    'role' => $role
        ]);
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $roles= Role::where('name', 'LIKE', "%{$keyword}%")->get();
            
        }else{
            $roles= Role::where('name', 'LIKE', "%{$keyword}%")->get();
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
                        <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên danh mục</th>
                        <th scope="col">Mô tả quyền</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($roles) >0){
                            $count = 0;   
                            foreach ($roles as $role){
                                        $count ++;   
                                        
                        ?>
                                <tr data-id="<?php echo $role->id  ?>" class="element">
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php echo route('role.edit', $role->id) ?>"><?php echo $role->name; ?></a></td>
                                    <td><?php echo $role->description; ?></td>
                                    <td><?php echo $role->created_at; ?></td>
                                    <td>
                                        <?php
                                            if(Gate::allows('role-edit')){
                                                ?>
                                                <a href="<?php echo route('role.edit', $role->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }
                                            if(Gate::allows('role-delete')){
                                                ?>
                                                <a href="<?php echo route('role.delete', $role->id);  ?>" data-id="<?php echo $role->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
        <a href="<?php echo route('role.list')  ?>" class="back">Quay lại</a>
        <?php
    }

    function pagination(Request $request){
        if($request->ajax()){
            $roles = Role::paginate(10);
            return view('admin.role.pagination',compact('roles'))->render();
        }
    }
}
