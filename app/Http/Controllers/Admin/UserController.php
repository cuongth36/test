<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'user']);
            return $next($request);
        });
    }

    function list(Request $request){
       
        $list_action = ['delete' => 'Xóa tạm thời'];
        $status = $request->input('status');
        if($status == 'trash'){
            $list_action = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $users = User::onlyTrashed()->paginate(10);
        }
        else{
            $user_id = Auth()->id();
            $users = User::where('id','!=', $user_id)->paginate(10);
        }
        foreach( $users as $key=> $value){
            if(Auth::id() == $value->id){
                unset($users[$key]);
            }
        }
        $count_user_active =  User::where('id', '!=', Auth::id())->count();
        $count_user_trash  = User::onlyTrashed()->count();
        $count =  [$count_user_active, $count_user_trash];
        return view('admin.user.list',compact('users', 'status', 'count', 'list_action'));
    }

    function pagination(Request $request){
        if($request->ajax()){
            $user_id = Auth()->id();
            $users = User::where('id','!=', $user_id)->paginate(10);
            return view('admin.user.pagination', compact('users'))->render();
        }
    }
    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword =  $request->input('keyword');
            $users = User::where('name', 'LIKE',"%{$keyword}%")->paginate(10);
           
        }else{
            $users = User::where('name', 'LIKE',"%{$keyword}%")->paginate(10);
        }
        ?>
        <div class="loader-wrapper">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <table class="table table-striped table-checkall table-user">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="checkall">
                        </th>
                        <th scope="col">STT</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Quyền</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count =0 ;
                        if(count($users) >0){
                            foreach($users as $user){
                                $count++ ;
                                ?>
                                <tr class="element" data-id="<?php echo $user->id ?>">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="<?php echo $user->id; ?>">
                                    </td>
                                     <td scope="row"><?php echo $count; ?></td>
                                     <td>
                                        <?php echo $user->name; ?>
                                     </td>
                                     <td>
                                        <?php echo $user->email; ?>
                                     </td>
                                     <td>
                                        <?php echo 'Admintrator'; ?>
                                     </td>
                                     <td>
                                        <?php echo $user->created_at; ?>
                                     </td>
                                     <td>
                                    
                                        <?php
                                            if(Gate::allows('user-edit')){
                                               ?>
                                               <a href="<?php  echo route('user.edit', $user->id); ?>" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            } 

                                            if(Gate::allows('user-delete')){
                                                ?>
                                                 <a href="<?php  echo route('user.delete', $user->id);?>" data-id="<?php echo $user->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                            <?php
                                            }
                                        ?>
                                </td>
                                </tr>
                            <?php
                            }
                        } else{
                            ?>
                             <tr>
                                <td colspan="7" class="bg-white">Không tìm thấy bản ghi</td>
                            </tr>
                           <?php 
                        }
                    ?>
                    
                </tbody>
            </table>
        <?php
        $users->links();
    }

    function add(){
        $roles = Role::orderBy('name', 'asc')->get();
        return view('admin.user.add',compact('roles'));
    }

    function store(Request $request){
        //validation form 

        $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:6|confirmed',
                'email' => 'required|string|unique:users|max:255'
            ],
            [
                'required' => ':attribute không được bỏ trống',
                'min' => 'Độ dài tối thiểu',
                'max' => 'Độ dài tối đa',
                'unique' => 'Email đã tồn tại',
                'confirmed' => 'Xác nhận mật khẩu không thành công'
            ],
            [
                'name' => 'Tên đăng nhập',
                'email' => 'email',
                'password' => 'Mật khẩu',
            ]
        );
        $user = User::create([
            'name' => $request->input('name'),
            'password'=> Hash::make($request->input('password')),
            'email' =>  $request->input('email'),
        ]);
        
        
        $list_role = $request->input('role_list');
        $user_role = User::find($user->id);
        $user_role->roles()->attach($list_role);
        return redirect('admin/user/list')->with('status','Thêm user thành công');
        
    }

    function delete($id){
        $user = User::find($id);
        $user->delete();
        return  response()->json([
            'status' => 1,
            'id' => $id,
            'message' => '<div class="alert alert-success">'. 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
            'user' => $user
        ]);
    }

    function edit($id){
        $user = User::find($id);
        $user_role = $user->hasAccess('sua-user');
        if(Auth::id() != $id  && ! $user_role){
            return redirect('403.html');
        }
        $roles = Role::orderBy('name', 'asc')->get();
            $data_role = $user->roles;
            $data_select = [];
            foreach($data_role as $item){
                $data_select[] = $item->id;
            }
        return view('admin.user.edit', compact('user', 'roles', 'data_select'));
    }

    function update(Request $request, $id){
        $user_role = User::find($id);
        $arg_rules = [
            'name' => 'required|string|max:255',
        ];
        $arg_message = [
            'required' => ':attribute không được bỏ trống',
            'name.max' => 'Độ dài tối đa',
            'string' => 'Chuỗi',
        ];
        $password = $request->input('password');
        if(!empty($password)){
            $arg_rules['password'] = 'string|min:6|confirmed';
            $arg_message['pasword.required'] = 'Mật khẩu không được bỏ trống';
            $arg_message['pasword.min'] = 'Độ dài tối thiểu của mật khẩu là 6 ký tự';
            $arg_message['password.confirmed'] = 'Mật khẩu không khớp';
            $password = trim(Hash::make($password));
        }else{
           
            $password = User::find($id)->password;
        }
        // $password_confirm = $request->input('password_confirmation');
        // if(!empty($password_confirm)){
        //     if(trim(Hash::make($password_confirm)) !== $password)
        //         return redirect("admin/user/edit/{$id}")->with('error', 'Bạn cần nhập mật khẩu trước khi cập nhật');
            
        // }
      
        Validator::make($request->all(),$arg_rules, $arg_message)->validate();
        User::where('id', $id)->update([
            'name' => trim($request->input('name')),
            'password' => $password,
        ]);

        $list_roles = $request->input('role_list');
        $user_role->roles()->detach();
        $user_role->roles()->attach($list_roles);
        return redirect('admin/user/list')->with('status', 'Bạn đã cập nhập thông tin thành công');
    }

    function action(Request $request){
        if($request->all()['id']){
             $list_check = $request->all()['id'];
            $action = $request->all()['infoAction'];
            if($action == 'forceDelete'){   
                $user = User::whereIn('id', $list_check);
                $data_user = $user->forceDelete();
               return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'data_user' => $data_user
            ]);
            }else if($action == 'restore'){
                $data_user =  User::withTrashed()->whereIn('id', $list_check)->restore();
                return response()->json([
                    'status' => 1,
                    'id' => $list_check,
                    'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                    'data_user' => $data_user
                ]);
            }else{
                $data_user = User::destroy($list_check);
                return response()->json(
                    [
                        'status' => 1,
                        'id' => $list_check,
                        'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                        'data_user' => $data_user
                    ]
                );
            }
        }
    }
    function logout(){
        
    }
}
