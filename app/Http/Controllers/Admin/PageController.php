<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class PageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'page']);
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
            $list_pages = Page::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
          
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $list_pages = Page::where('status', '=', 'pendding')->paginate(10);
        }else{
            $list_pages = Page::where('status', '=', 'approve')->paginate(10);
        }
        $count_page_active = Page::all()->where('status','=', 'approve')->count();
        $count_page_trash = Page::onlyTrashed()->count();
        $count_page_pedding = Page::all()->where('status', '=', 'pendding')->count();
        $count = [$count_page_active,$count_page_pedding, $count_page_trash];
        return view('admin.page.list', compact( 'list_pages','count', 'list_action'));
    }

    function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $list_pages = Page::where('status', '=', 'approve')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $list_pages = Page::where('status', '=', 'pendding')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $list_pages = Page::where('status', '=', 'approve')->paginate(10);
                  }else{
                    $list_pages = Page::onlyTrashed()->paginate(10);
                 }
            }
            return view('admin.page.pagination',compact('list_pages'))->render();
        }
    }
    
    function add(){
        return view('admin.page.add');
    }

    function store(Request $request){
        //validation 
        $request->validate([
            'title' => 'required|max:255',
            'slug'  => 'max:255|required',
            'content' => 'required',
        ],
        [
            'title' => ':attribute không được bỏ trống',
            'slug.max' => 'Độ dài tối đa của slug là 255 ký tự',
            'title.max' => 'Độ dài tối đa của tiêu đề là 255 ký tự',
        ],
        [
            'title' => 'Tiêu đề',
            'content' => 'Nội dung'
        ]);

        $slug = $request->input('slug');
        $slug_exit = Page::where('slug' , '=' , $slug)->get();
         if(count($slug_exit) > 0){
             $array = [1, 2, 3, 4, 5,6,7,8,9];
             $random = Arr::random($array);
             $slug = $slug. '-'.$random;
         }
         Page::create([
                'title' => $request->input('title'),
                'slug'  => $slug,
                'content'=> $request->input('content'),
                'status' => $request->input('status'),
                'creator' => Auth::user()->name,
                'user_id' => Auth::id()
        ]);
        return redirect('admin/page/list')->with('status', 'Thêm trang mới thành công');
    }

    
    
    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $pages= Page::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $pages= Page::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
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
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">STT</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Người tạo</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Người chỉnh sửa</th>
                            <th scope="col">Ngày sửa</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($pages->total() >0){
                            $count = 0;   
                            foreach ($pages as $page){
                                        $count ++;   
                            ?>
                                <tr data-id="<?php echo $page->id ?>" class="element">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="<?php echo $page->id; ?>">
                                    </td>
                                    <td scope="row"><?php echo $count; ?></td>
                                    <td><a href="<?php $page->title ?>"><?php echo $page->title; ?></a></td>
                                    <td><?php echo $page->creator; ?></td>
                                    <td><?php echo $page->created_at; ?></td>
                                    <td><?php echo $page->editor; ?></td>
                                    <td><?php echo $page->updated_at; ?></td>
                                    <td>
                                        <?php 
                                            if(Gate::allows('page-edit')){
                                                ?>
                                                <a href="<?php echo route('page.edit', $page->id) ?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                            <?php
                                            }
                                            if(Gate::allows('page-delete')){
                                                ?>
                                                <a href="<?php echo route('page.delete', $page->id) ?>" data-id="<?php echo $page->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
       echo $pages->links();
    }

    function edit($id){
        $page = Page::find($id);
        return view('admin.page.edit', compact('page'));
    }

    function update(Request $request, $id){
        $request->validate([
            'title' => 'required|max:255',
            'slug'  => 'max:255|required',
            'content' => 'required',
        ],
        [
            'title' => ':attribute không được bỏ trống',
            'slug.max' => 'Độ dài tối đa của slug là 255',
            'title.max' => 'Độ dài tối đa của tiêu đề là 255',
        ],
        [
            'title' => 'Tiêu đề',
            'content' => 'Nội dung'
        ]);
       
        $slug = $request->input('slug');
        $slug_current_id = Page::where('slug' , '=' , $slug)->where('id', '=', $id)->get();
        if(count($slug_current_id) > 0){
            $slug = $slug;
        }else{
            $slug_exit = Page::where('slug' , '=' , $slug)->get();
            if(count($slug_exit) > 0){
                $array = [1, 2, 3, 4, 5,6,7,8,9];
                $random = Arr::random($array);
                $slug = $slug. '-'.$random;
            }
        }

        Page::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug'  => $slug,
            'content' => $request->input('content'),
            'editor' => Auth::user()->name,
        ]);
      return redirect('admin/page/list')->with('status', 'Bạn đã cập nhập thành công');
    }

    function delete($id){
      $page = Page::find($id);
      $page->delete();
      return response()->json([
        'status' => 1,
        'id' => $id,
        'message' => '<div class="alert alert-success">' . 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
        'page' => $page
    ]);
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $page =  Page::whereIn('id', $list_check)->update([
                 'status' => 'approve'
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'page' => $page
        ]);
        }else if($action == 'delete'){
            $page =  Page::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'page' => $page
            ]);
        }else if($action == 'restore'){
            $page =  Page::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'page' => $page
            ]);
        }else{
            $page =  Page::whereIn('id', $list_check);
            $page ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'page' => $page
            ]);
        }
        
     }
}
