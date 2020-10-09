<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Http\File;
use App\Slider;
use Illuminate\Support\Facades\Gate;

class SliderController extends Controller
{
    function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }

    function list(){
        $sliders = Slider::orderBy('slider_sort', 'asc')->paginate(10);
        return view('admin.slider.list', compact('sliders'));
    }
    

    function add(){
        $num_slider = Slider::count();
        $num_slider += 1;
        return view('admin.slider.add',compact('num_slider'));
    }


    function store(Request $request){
        $request->validate([
            'title' => 'required|max:255|unique:sliders',
            'link' => 'max:255',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ],
        [
            'required' => ':attribute không được bỏ trống trường này',
            'unique' => 'Tên slider đã tồn tại',
            'link.max' => 'Độ dài lớn nhất của link là 255 ký tự',
            'title.max' => 'Độ dài lớn nhất của tiêu đề là 255 ký tự',
            'file'      => 'Tên tệp',
            'mimes'     => 'Tải tệp lỗi, tệp tin phải là 1 trong các định dạng sau:peg,png,jpg,gif,svg'
        ],
        [
            'title' => 'Slider',
            'link' => 'Đường dẫn',
            'file' => 'Tên tệp'
        ]);
        
        $slider_sort = $request->input('slider_sort');
        $slider = Slider::where('slider_sort', '=' , "$slider_sort")->get();
        if(count($slider) > 0){
            return redirect('admin/slider/add')->with('error_sort', 'Thứ tự bạn vừa chọn đã tồn tại, vui lòng chọn thứ tự khác.');
        }

        if($request->hasFile('file')){
            $file = $request->file('file');
           
            $file_name = time(). '_'. $file->getClientOriginalName();
          
            $file_size = $file->getSize();
            if($file_size > 5000000)
                return redirect('admin/slider/add')->with('file_size', 'Tệp tin tải lên vượt quá kích thước cho phép');
            $file->move('public/uploads/', $file_name);
            // luu duong dan dưới local
            $thumbnail = 'public/uploads/'. $file_name;
            
        }
      
        Slider::create([
            'title' => $request->input('title'),
            'thumbnail' => $thumbnail,
            'link' => $request->input('link'),
            'slider_sort' =>  $slider_sort,
        ]);
        return redirect('admin/slider/list')->with('status', 'Thêm slider thành công');
    }

    function pagination(Request $request){
        if($request->ajax()){
            $sliders = Slider::orderBy('slider_sort', 'asc')->paginate(10);
            return view('admin.slider.pagination',compact('sliders'))->render();
        }
    }

 
    function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $slider= Slider::where('title', 'LIKE', "%{$keyword}%")->get();
            
        }else{
            $slider= Slider::where('title', 'LIKE', "%{$keyword}%")->get();
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
                        if(count($slider) >0){
                            $count = 0;   
                            foreach ($slider as $item){
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
                                    
                                    if(Gate::allows('slider-edit')){
                                               ?>
                                        <a href="<?php echo route('slider.edit', $item->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <?php
                                        }
                                      if(Gate::allows('slider-delete')){
                                          ?>
                                          <a href="<?php echo route('slider.delete', $item->id);  ?>" data-id="<?php echo $item->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
        <a href="<?php echo route('slider.list')  ?>" class="back">Quay lại</a>
        <?php
    }
    function edit($id){
        $slider = Slider::find($id);
        $num_slider = Slider::count();
        return view('admin.slider.edit', compact('slider', 'num_slider'));
    }

    function update(Request $request, $id){
        $request->validate(
        [
            'title' =>   [
                'required',
                'max:255',
                Rule::unique('sliders')->ignore($id),
            ],
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'link' => 'max:255'
        ],
        [
            'required' => ':attribute không được bỏ trống',
            'unique' => 'Tên slider đã tồn tại',
            'link.max' => 'Độ dài lớn nhất của link là 255 ký tự',
            'title.max' => 'Độ dài lớn nhất của tiêu đề là 255 ký tự',
            'file'      => 'Tên tệp',
            'mimes'     => 'Tải tệp lỗi, tệp tin phải là 1 trong các định dạng sau:peg,png,jpg,gif,svg'
        ],
        [
            'title' => 'Slider',
            'link' => 'Đường dẫn',
            'file' => 'Tên tệp'
        ]);
       
        $slider_info = Slider::find($id);
        $thumbnail = $slider_info->thumbnail;
        $slider_sort = $slider_info ->slider_sort;
     
        if($request->has('slider_sort')){
            $slider_sort = $request->input('slider_sort');
            $check_slider_sort =$slider_info ->slider_sort;
            $num_slider = Slider::where('slider_sort', '=' , "$slider_sort")->get(); 
            if(count($num_slider) > 0 && $slider_sort != $check_slider_sort ){
                return redirect("admin/slider/edit/$id")->with('error_sort', 'Thứ tự bạn vừa chọn đã tồn tại, vui lòng chọn thứ tự khác.');
            }
        }

     
        if($request->hasFile('file')){
            $file = $request->file('file');
            $file_name = time(). '_'. $file->getClientOriginalName();
            $file_size = $file->getSize();
            if($file_size > 5000000)
                return redirect("admin/slider/edit/$id")->with('file_size', 'Tệp tin tải lên vượt quá kích thước cho phép');
            $file->move('public/uploads/', $file_name);
            // luu duong dan dưới local
            $thumbnail = 'public/uploads/'. $file_name;
            
        }

        Slider::where('id', $id)->update([
            'title' => $request->input('title'),
            'thumbnail' => $thumbnail,
            'link' => $request->input('link'),
            'slider_sort' => $slider_sort,
        ]);
       
      return redirect('admin/slider/list')->with('status', 'Bạn đã cập nhập slider thành công');
    }

    function delete($id){
        $menu = Slider::find($id);
        $number_menu_child = Slider::all()->where('parent_id', '=', $id)->count();
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
