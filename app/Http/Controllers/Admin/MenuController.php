<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Menu;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function($request,  $next){
            session(['module_active' => 'menu']);
            return $next($request);
        });
    }

    function list(){
        $menus = Menu::orderBy('menu_sort', 'asc')->get();
        $menus = $this->data_tree($menus);
        return view('admin.menu.list', compact('menus'));
    }
    

    function add(){
        $menus = Menu::all(); 
        $resutl = $this->data_tree($menus);
        $num_menu = Menu::where('parent_id', '=', '0')->count();
        $num_menu += 1;
        return view('admin.menu.add',compact('resutl', 'num_menu'));
    }


    function store(Request $request){
        $request->validate([
            'title' => 'required|max:255|unique:menus',
            'slug' => 'max:255|required',
            'link' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên danh mục đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'title' => 'Danh mục',
            'link' => 'Đường dẫn'
        ]);
        
        $menu_sort = 0;
        if($request->input('category_parent') == 0){
            $menu_sort = $request->input('menu_sort');
            $menu = Menu::where('menu_sort', '=' , "$menu_sort")->get();
            if(count($menu) > 0){
                return Redirect::back()->withErrors(['Thứ tự bạn vừa chọn đã tồn tại, vui lòng chọn thứ tự khác.']);
            }
        }
       
        $slug = $request->input('slug');
        $slug_exit = Menu::where('slug' , '=' , $slug)->get();
         if(count($slug_exit) > 0){
             $array = [1, 2, 3, 4, 5,6,7,8,9];
             $random = Arr::random($array);
             $slug = $slug. '-'.$random;
         }

        Menu::create([
            'title' => $request->input('title'),
            'parent_id' => $request->input('category_parent'),
            'slug' =>$slug,
            'link' => $request->input('link'),
            'menu_sort' => $menu_sort,
        ]);
        return redirect('admin/menu/list')->with('status', 'Thêm menu thành công');
    }

   public function data_tree($data, $parent_id = 0 , $level = 0){
        $result = [];
        foreach($data as $item){
            if($item['parent_id'] == $parent_id){
                $item['level'] = $level;
                $result[] = $item;
                // unset($data[$item['id']]);
                $child = $this->data_tree($data, $item['id'], $level + 1);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $categories= Menu::where('title', 'LIKE', "%{$keyword}%")->get();
            
        }else{
            $categories= Menu::where('title', 'LIKE', "%{$keyword}%")->get();
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
                        <th scope="col">Slug</th>
                        <th scope="col">Link</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($categories) >0){
                            $count = 0;   
                            foreach ($categories as $item){
                                        $count ++;   
                        ?>
                                <tr data-id="<?php echo $item->id  ?>" class="element">
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php echo $item->title ?>"><?php echo $item->title; ?></a></td>
                                    <td><?php echo $item->slug; ?></td>
                                    <td><?php echo $item->link; ?></td>
                                    <td><?php echo $item->created_at; ?></td>
                                    <td>
                                        <?php 
                                            if(Gate::allows('menu-edit')){
                                                ?>
                                                <a href="<?php echo route('menu.edit', $item->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }

                                            if(Gate::allows('menu-delete')){
                                                ?>
                                                <a href="<?php echo route('menu.delete', $item->id);  ?>" data-id="<?php echo $item->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
        <a href="<?php echo route('menu.list')  ?>" class="back">Quay lại</a>
        <?php
    }
    function edit($id){
        $menus = Menu::find($id);
        $item_menus = Menu::all()->where('id', '!=', $id)->where('parent_id', '!=', $id);
        $list_menus = $this->data_tree($item_menus);
        $num_menu = Menu::where('parent_id', '=', '0')->count();
        $num_menu += 1;
        return view('admin.menu.edit', compact('menus','list_menus', 'num_menu'));
    }

    function update(Request $request, $id){
        $request->validate(
        [
            'title' =>   [
                'required',
                'max:255',
                Rule::unique('menus')->ignore($id),
            ],
            'slug' => 'max:255|required',
            'link' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên danh mục đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'title' => 'Danh mục',
            'link' => 'Đường dẫn'
        ]);
       

        $get_parent_id = Menu::find($id)->parent_id;
       
        if( $request->input('category_parent')){
            $get_parent_id = $request->input('category_parent');
        }

        $menu_sort = Menu::find($id)->menu_sort;
        
        if($request->has('menu_sort')){
            $menu_sort = $request->input('menu_sort');
            $check_menu_sort = Menu::find($id)->menu_sort;
            $menu = Menu::where('menu_sort', '=' , "$menu_sort")->get();
            if(count($menu) > 0 && $menu_sort != $check_menu_sort){
                return redirect("admin/menu/edit/$id")->with('error_sort', 'Thứ tự bạn vừa chọn đã tồn tại, vui lòng chọn thứ tự khác.');
            }
        }
        
         $slug = $request->input('slug');
         $slug_current_id = Menu::where('slug' , '=' , $slug)->where('id', '=', $id)->get();
         if(count($slug_current_id) > 0){
             $slug = $slug;
         }else{
             $slug_exit = Menu::where('slug' , '=' , $slug)->get();
             if(count($slug_exit) > 0){
                 $array = [1, 2, 3, 4, 5,6,7,8,9];
                 $random = Arr::random($array);
                 $slug = $slug. '-'.$random;
             }
         }

        Menu::where('id', $id)->update([
            'title' => $request->input('title'),
            'parent_id' => $get_parent_id,
            'slug' =>$slug,
            'link' => $request->input('link'),
            'menu_sort' => $menu_sort,
        ]);
       
      return redirect('admin/menu/list')->with('status', 'Bạn đã cập nhập menu thành công');
    }

    function delete($id){
        $menu = Menu::find($id);
        $number_menu_child = Menu::all()->where('parent_id', '=', $id)->count();
        if($number_menu_child > 0){
            return response()->json(
                [
                    'status' => 0,
                    'message' => '<div class="alert alert-danger">' . 'Bạn cần xóa danh mục con trước khi thực hiện xóa danh mục cha'. '</div>'
                ]
            );
        }else{
            $menu->delete();
            return response()->json([
                    'status' => 1,
                    'id' => $id,
                    'message' => '<div class="alert alert-success">' . 'Bạn đã xóa bản ghi thành công'. '</div>',
                    'menu' => $menu
            ]);
        }
        
    }
}
