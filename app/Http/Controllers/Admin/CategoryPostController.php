<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\CategoryPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class CategoryPostController extends Controller
{
   function __construct()
   {
       $this->middleware(function($request,$next){
            session(['module_active' => 'post']);
            return $next($request);
       });
   }
    function list(){
        $categories = CategoryPost::all();
        $categories = $this->data_tree($categories);
        return view('admin.categoryPost.list', compact('categories'));
    }
    

    function add(){
        $categories = CategoryPost::all(); 
        $resutl = $this->data_tree($categories);
        return view('admin.categoryPost.add',compact('resutl'));
    }


    function store(Request $request){
        $request->validate([
            'title' => 'required|max:255|unique:category_posts',
            'slug' => 'max:255|required'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên danh mục đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'title' => 'Danh mục',
        ]);

        $slug = $request->input('slug');
        $slug_exit = CategoryPost::where('slug' , '=' , $slug)->get();
         if(count($slug_exit) > 0){
             $array = [1, 2, 3, 4, 5,6,7,8,9];
             $random = Arr::random($array);
             $slug = $slug. '-'.$random;
         }
        CategoryPost::create([
            'title' => $request->input('title'),
            'parent_id' => $request->input('category_parent'),
            'slug' =>$slug,
        ]);
        return redirect('admin/post/category/list')->with('status', 'Thêm danh mục thành công');
    }

    
   public  function data_tree($data, $parent_id = 0, $level =0){
      
        $result = [];
        foreach($data as $item){
            if($item['parent_id'] == $parent_id){
                $item['level'] = $level; 
                $result[] = $item;
                // unset($data[$item['id']]);
                $child =$this-> data_tree($data, $item['id'], $level + 1); 
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }

    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $categories= CategoryPost::where('title', 'LIKE', "%{$keyword}%")->get();
            
        }else{
            $categories= CategoryPost::where('title', 'LIKE', "%{$keyword}%")->get();
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
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($categories) >0){
                            $count = 0;   
                            foreach ($categories as $category){
                                        $count ++;   
                        ?>
                                <tr data-id="<?php echo $category->id  ?>" class="element">
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php echo route('category.edit', $category->id) ?>"><?php echo $category->title; ?></a></td>
                                    <td><?php echo $category->slug; ?></td>
                                    <td><?php echo $category->created_at; ?></td>
                                    <td>
                                        <?php 
                                            if(Gate::allows('post-category-edit')){
                                                ?>
                                                <a href="<?php echo route('category.edit', $category->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }

                                            if(Gate::allows('post-category-delete')){
                                                ?>
                                                <a href="<?php echo route('category.delete', $category->id);  ?>" data-id="<?php echo $category->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
        <a href="<?php echo route('category.list')  ?>" class="back">Quay lại</a>
        <?php
    }
    function edit($id){
        $categories = CategoryPost::find($id);
        $item_categories = CategoryPost::all()->where('id', '!=', $id)->where('parent_id', '!=', $id);
        $list_categories = $this->data_tree($item_categories);
        return view('admin.categoryPost.edit', compact('categories','list_categories'));
    }

    function update(Request $request, $id){
        $request->validate(
        [
            'title' =>   [
                'required',
                'max:255',
                Rule::unique('product_categories')->ignore($id),
            ],
            'slug' => 'max:255|required'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên danh mục đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'title' => 'Danh mục',
        ]);
        $get_parent_id = CategoryPost::find($id)->parent_id;
       
        if( $request->input('category_parent')){
            $get_parent_id = $request->input('category_parent');
        }

         $slug = $request->input('slug');
         $slug_current_id = CategoryPost::where('slug' , '=' , $slug)->where('id', '=', $id)->get();
         if(count($slug_current_id) > 0){
             $slug = $slug;
         }else{
             $slug_exit = CategoryPost::where('slug' , '=' , $slug)->get();
             if(count($slug_exit) > 0){
                 $array = [1, 2, 3, 4, 5,6,7,8,9];
                 $random = Arr::random($array);
                 $slug = $slug. '-'.$random;
             }
         }

        CategoryPost::where('id', $id)->update([
            'title' => $request->input('title'),
            'parent_id' => $get_parent_id,
            'slug' =>$slug,
        ]);
       
      return redirect('admin/post/category/list')->with('status', 'Bạn đã cập nhập thành công');
    }

    function delete($id){
        $category = CategoryPost::find($id);
        $number_category_child = CategoryPost::all()->where('parent_id', '=', $id)->count();
        if($number_category_child > 0){
            return response()->json(
                [
                    'status' => 0,
                    'message' => '<div class="alert alert-danger">' . 'Bạn cần xóa danh mục con trước khi thực hiện xóa danh mục cha'. '</div>'
                ]
            );
        }else{
            $category->delete();
            return response()->json([
                    'status' => 1,
                    'id' => $id,
                    'message' => '<div class="alert alert-success">' . 'Bạn đã xóa bản ghi  thành công'. '</div>',
                    'category' => $category
            ]);
        }
        
    }

   
}
