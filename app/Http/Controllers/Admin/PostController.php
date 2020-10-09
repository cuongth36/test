<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CategoryPostController;
use App\Post;
use App\CategoryPost;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    
    function __construct()
    {
    
        $this->middleware(function($request, $next){
            session(['module_active' => 'post']);
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
            $posts = Post::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $posts = Post::where('status', '=', 'pendding')->paginate(10);
        }else{
            $posts = Post::where('status', '=', 'approve')->paginate(10);
        }
        $count_category_active = Post::all()->where('status','=', 'approve')->count();
        $count_category_trash = Post::onlyTrashed()->count();
        $count_category_pedding = Post::all()->where('status', '=', 'pendding')->count();
        $count = [$count_category_active,$count_category_pedding, $count_category_trash];
        $data = CategoryPost::all();
        $object = new CategoryPostController();
        $cate= $object->data_tree($data);
        return view('admin.post.list', compact('cate','posts', 'list_action', 'count'));
    }

    function add(){
        $data = CategoryPost::all();
        $object = new CategoryPostController();
        $categories= $object->data_tree($data);
        return view('admin.post.add', compact('categories'));
    }

    function store(Request $request){
        $request->validate([
            'title' => 'required|max:255|unique:posts',
            'category_parent' => 'required',
            'file'  => 'image|mimes:jpeg,png,jpg,gif,svg',
            'slug'  => 'max:255|required',
            'description' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'image'     => 'Tệp tin tải lên phải có định dạng jpeg, png, jpg, gif, svg',
            'unique' => 'Tên danh mục đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255'
        ],
        [
            'title' => 'Tiêu đề',
            'category_parent' => 'Danh mục',
            'file' => 'Tệp tin',
            'description'  => 'Mô Tả ngắn'
        ]);

        $input = $request->all();
        $input['thumbnail'] = '';
        if($request->hasFile('file')){
            $file = $request->file;
            // Lay duoi file
            $filename = $file->getClientOriginalName();
            $filename = time(). '-' .$filename;
            $file_size = $file->getSize();

            if($file_size > 5000000){
                return redirect('admin/post/add')->with('file_error', 'file upload vượt quá kích thước cho phép');
            }

            $file->move('public/uploads',  $filename );
            $thumbnail = 'public/uploads/' .  $filename;
            $input['thumbnail'] = $thumbnail;
        }
        if($request->input('category_parent') == 0){
            return redirect('admin/post/add')->with('category_error', 'Bạn cần chọn danh mục bài viết');
        }

       
         $slug = $request->input('slug');
         $slug_exit = Post::where('slug' , '=' , $slug)->get();
         if(count($slug_exit) > 0){
             $array = [1, 2, 3, 4, 5,6,7,8,9];
             $random = Arr::random($array);
             $slug = $slug. '-'.$random;
         }
        Post::create([
                'title' => $request->input('title'),
                'slug' => $slug ,
                'description' => $request->input('description'),
                'content' => $request->input('content'),
                'creator' => Auth::user()->name,
                'user_id' => Auth::id(),
                'status' => $request->input('status'),
                'editor' => ' ',
                'category_id' => $request->input('category_parent'),
                'thumbnail' =>$input['thumbnail']
        ]);
        return redirect('admin/post/list')->with('status', 'Bạn đã thêm bài viết thành công');
    }

    function edit($id){
        $data = CategoryPost::all();
        $object = new CategoryPostController();
        $categories= $object->data_tree($data);
        $post = Post::find($id);
        return  view('admin/post/edit', compact('post', 'categories'));
    }

    function update(Request $request, $id){
        $request->validate([
            'title' =>[
                'required',
                Rule::unique('posts')->ignore($id),
            ],
            'category_parent' => 'required',
            'file'  => 'image|mimes:jpeg,png,jpg,gif,svg',
            'slug'  => 'max:255|required',
            'description' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'image'     => 'Tệp tin tải lên phải có định dạng jpeg, png, jpg, gif, svg',
            'max' => 'Độ dài lớn nhất là 255',
            'unique' => 'Tên bài viết đã tồn tại',
        ],
        [
            'title' => 'Tiêu đề',
            'category_parent' => 'Danh mục',
            'file' => 'Tệp tin',
            'description'  => 'Mô Tả ngắn'
        ]);
       
        $input = $request->all();
        $get_thumbnail = Post::find($id)->thumbnail;
        $get_category = Post::find($id)->category_id;
        $input['thumbnail'] =  $get_thumbnail;
    
        if($request->hasFile('file')){
            $file = $request->file;
            $filename = $file->getClientOriginalName();
            $filename = time(). '-' .$filename;
            $file_size = $file->getSize();

            if($file_size > 5000000){
                return redirect('admin/post/add')->with('file_error', 'file upload vượt quá kích thước cho phép');
            }

            $file->move('public/uploads',  $filename );
            $thumbnail = 'public/uploads/' .  $filename;
            $input['thumbnail'] = $thumbnail;
        }

        $slug = $request->input('slug');
        $slug_current_id = Post::where('slug' , '=' , $slug)->where('id', '=', $id)->get();
        if(count($slug_current_id) > 0){
            $slug = $slug;
        }else{
            $slug_exit = Post::where('slug' , '=' , $slug)->get();
            if(count($slug_exit) > 0){
                $array = [1, 2, 3, 4, 5,6,7,8,9];
                $random = Arr::random($array);
                $slug = $slug. '-'.$random;
            }
        }
         
        Post::where('id', $id)->update(
            [
                'title' => $request->input('title'),
                'slug' => $slug ,
                'description' => $request->input('description'),
                'content' => $request->input('content'),
                'creator' => Auth::user()->name,
                'user_id' => Auth::id(),
                'editor' => Auth::user()->name,
                'status' => $request->input('status'),
                'category_id' =>$get_category,
                'thumbnail' =>$input['thumbnail']
            ]
        );
        return redirect('admin/post/list')->with('status', 'Bạn đã cập nhật thành công');
    }

    function duplicatedPost($id,$title){
        $numberPost = Post::all()->where('id','!=', $id)->where('title', $title)->count();
        if($numberPost > 0){
            return redirect('admin/post/edit')->with('title_error', 'Bài viết đã tồn tại trong hệ thống');
        }
    }

    function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $posts = Post::where('status', '=', 'approve')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $posts = Post::where('status', '=', 'pendding')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $posts = Post::where('status', '=', 'approve')->paginate(10);
                  }else{
                    $posts = Post::onlyTrashed()->paginate(10);
                 }
            }
            $data = CategoryPost::all();
            $object = new CategoryPostController();
            $cate= $object->data_tree($data);
            return view('admin.post.pagination',compact('posts', 'cate'))->render();
        }
    }
   
    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $posts= Post::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $posts= Post::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }
            $data = CategoryPost::all();
            $object = new CategoryPostController();
            $categories= $object->data_tree($data);
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
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">STT</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Người tạo</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Người chỉnh sửa</th>
                            <th scope="col">Ngày sửa</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $count = 0;   
                            if($posts->total() >0){
                                foreach ($posts as $post){
                                    $count ++;   
                                ?>
                                    <tr data-id="<?php echo $post->id  ?>" class="element">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="<?php echo $post->id  ?>">
                                        </td>
                                        <td scope="row"><?php echo $count; ?></td>
                                        <td><img src="<?php echo url($post->thumbnail) ?>" alt=""></td>
                                        <td><a href="<?php echo $post->title ?>"><?php echo $post->title; ?></a></td>
                                        <?php
                                            
                                            foreach ($categories as $value){
                                                if($post->category_id == $value->id){
                                                    ?>
                                                     <td><?php echo str_repeat('- ', $value->level) . $value->title ?></td>
                                                <?php
                                                }
                                            }
                                            
                                        ?>
                                            <td><?php echo  $post->creator ?></td>
                                            <td><?php echo  $post->created_at ?></td>
                                            <td><?php echo  $post->editor ?></td>
                                            <td><?php echo  $post->updated_at ?></td>
                                        <td>
                                            <?php
                                                if(Gate::allows('post-edit')){
                                                    ?>
                                                    <a href="<?php echo route('post.edit', $post->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                                <?php
                                                }

                                                if(Gate::allows('post-delete')){
                                                    ?>
                                                     <a href="<?php echo route('post.delete', $post->id);  ?>" data-id="<?php echo $post->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
                                <td colspan="7" class="bg-white">Không tìm thấy bản ghi</td>
                            </tr>
                           <?php 
                        }
                            
                        ?>
                        
                    </tbody>
        </table>
        <?php
       echo $posts->links();
    }


    function delete($id){
        $post = Post::find($id);
        $post->delete();
        return response()->json([
            'status' => 1,
            'id' => $id,
            'message' => '<div class=" alert alert-success ">'. 'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>',
            'post' => $post
        ]);
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $categories =  Post::whereIn('id', $list_check)->update([
                 'status' => 'approve'
             ]);
         return response()->json(
            [
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
                'categories' => $categories
            ]
         );
        }else if($action == 'delete'){
            $categories =  Post::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'categories' => $categories
            ]);
        }else if($action == 'restore'){
            $categories =  Post::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'categories' => $categories
            ]);
        }else{
            $categories =  Post::whereIn('id', $list_check);
            $categories ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'categories' => $categories
            ]);
        }
    }
}
