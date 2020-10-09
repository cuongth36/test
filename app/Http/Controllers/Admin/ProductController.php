<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProductCategoryController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Product;
use App\ProductCategories;
use App\FeatureImage;
use App\Color;
use App\Size;
use App\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function($request, $next){
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
            $products = Product::onlyTrashed()->paginate(10);
        }else if ($status == 'pendding'){
            $list_action = [
                'approve' => 'Phê duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $products = Product::where('status', '=', '0')->paginate(10);
        }else{
            $products = Product::where('status', '=', '1')->paginate(10);
        }
        $count_product_active = Product::all()->where('status','=', '1')->count();
        $count_product_trash = Product::onlyTrashed()->count();
        $count_product_pedding = Product::all()->where('status', '=', '0')->count();
        $count = [$count_product_active,$count_product_pedding, $count_product_trash];
        $cate= ProductCategories::all();
        $data_qty = [];
        foreach($products as $item){
            $data_qty[] = $item->id;
        }
        $data_qty = implode(',',$data_qty);        
        $product_qty = '';
        if(!empty($data_qty)){
            $product_qty = DB::select("SELECT SUM(product_attribute.amount) as total_qty, max(product_attribute.product_id) as product_id FROM products  INNER JOIN product_attribute  ON products.id = product_attribute.product_id  WHERE product_attribute.product_id IN ($data_qty)  GROUP BY products.id");
        }
        return view('admin.product.list', compact('cate','product_qty', 'products','list_action', 'count'));
    }

    function add(){
        $data = ProductCategories::all();
        $object = new ProductCategoryController();
        $categories= $object->data_tree($data);
        $colors = Color::where('status', '1')->orderBy('title', 'asc')->get();
        $sizes = Size::where('status', '1')->orderBy('name', 'asc')->get();
        return view('admin.product.add', compact('colors','categories', 'sizes'));
    }

    function store(Request $request){
        $discount = $request->input('discount');
        $slug = $request->input('slug');
        $arg_rules = [
            'title' => 'required|max:255|unique:products',
            'category_parent' => 'required',
            'file'  => 'image|mimes:jpeg,png,jpg,gif,svg',
            'content'=> 'required',
            'price' => 'required|regex:/^[0-9]+$/',
            'colors' => 'required',
            'slug'   => 'required',
            // 'sizes'  => 'required',
            'quantity' => 'required',
            'description' => 'max:255'
        ];

        $arg_message = [
            'title.required' => 'Tiêu sản phẩm không được bỏ trống',
            'category_parent.required' => 'Danh mục không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
            'slug.required' => 'Slug không được bỏ trống',
            'price.required' => 'Giá không được bỏ trống',
            'colors.required' => 'Màu không được bỏ trống',
            // 'sizes.required' => 'Màu không được bỏ trống',
            'quantity.required' => 'Số lượng không được bỏ trống',
            'image'     => 'Tệp tin tải lên phải có định dạng jpeg, png, jpg, gif, svg,webp',
            'price.regex' => 'Bạn chỉ được nhập số cho trường này',
            'title.unique' => 'Tên sản phẩm đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255',
            'description'  => 'Mô tả ngắn',
        ];

        if(!empty($discount)){
            $arg_rules ['discount'] = 'regex:/^[0-9]{1,2}$/'; 
            $arg_message['discount.integer'] = 'Giảm giá phải là số';
            $arg_message['discount.size'] = 'Giảm giá có tối đa 2 số';
        }

        $validator = Validator::make($request->all(),$arg_rules, $arg_message)->validate();

        $input = $request->all();
        $input['thumbnail'] = '';
        $arr_image = [];
        if($request->hasFile('file')){
            $file = $request->file;
            // Lay duoi file
            $filename = $file->getClientOriginalName();
            $filename = time(). '-' .$filename;
            $file_size = $file->getSize();

            if($file_size > 5000000){
                return redirect('admin/product/add')->with('file_error', 'file upload vượt quá kích thước cho phép');
            }

            $file->move('public/uploads/',  $filename );
            $thumbnail = 'public/uploads/' .  $filename;
            $input['thumbnail'] = $thumbnail;
        }

        if($request->hasFile('feature-image')){
            $file = $request->file('feature-image');
            foreach($file  as $key => $item){
                $filename = $item->getClientOriginalName();
                $filename = time(). '-' .$filename;
                $item->move('public/uploads/', $filename);
                $feature_image = 'public/uploads/' .$filename;
                $arr_image[] = $feature_image;
            }
           
        }
        if($request->input('category_parent') == '0'){
            return redirect('admin/product/add')->with('category_error', 'Bạn cần chọn danh mục sản phẩm');
        }
       
        $slug_exit = Product::where('slug' , '=' , $slug)->get();
        if(count($slug_exit) > 0){
            $array = [1, 2, 3, 4, 5,6,7,8,9];
            $random = Arr::random($array);
            $slug = $slug. '-'.$random;
        }
        // dd($input['thumbnail']);
        
        if($request->input('product_hot')){
            $product_hot = $request->input('product_hot');
        }else{
            $product_hot = 0;
        }

        $products = Product::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'thumbnail' => $input['thumbnail'],
            'status' => $request->input('status'),
            'quantity' => $request->input('qty'),
            'product_hot' => $product_hot,
            'category_id' => $request->input('category_parent')
        ]);

        $product_id = $products->id;
        $input['feature_image'] = $arr_image;
        $item_image = implode(',',  $input['feature_image']);
        FeatureImage::create([
            'product_id' => $product_id,
            'feature_image' =>$item_image, 
        ]);
        
        $color_id = $request->input('colors');
        $num_qty = $request->input('quantity');
        $size_id = $request->input('sizes');

        foreach ($color_id as $key => $value) {
            if(!empty($value)){
                $product_attr = new ProductAttribute;
                $product_attr->product_id = $product_id;
                $product_attr->color_id = $value;
                $product_attr->size_id = $size_id[$key];
                $product_attr->amount = $num_qty[$key];
                $product_attr->save();
            }
        }
        return redirect('admin/product/list')->with('status', 'Bạn đã thêm sản phẩm thành công');
    }

    function edit($id){
        $product = Product::find($id);
        $list_feature_image = [];
        $feature_image = $product->feature_image;
        foreach($feature_image as $item){
            $data = explode(',', $item->feature_image);
            $list_feature_image[] =$data;
        }
        $list_colors = Color::where('status', '1')->orderBy('title', 'asc')->get();
        $list_sizes = Size::where('status', '1')->orderBy('name', 'asc')->get();
        $data = ProductCategories::all();
        $data_cate = new ProductCategoryController();
        $categories = $data_cate->data_tree($data);
        $data_attr = DB::select("SELECT prd_attr.*, prd.id, prd.price, colors.id, sizes.id FROM product_attribute as prd_attr INNER JOIN products as prd on prd_attr.product_id = prd.id INNER JOIN colors  on prd_attr.color_id = colors.id INNER JOIN sizes on prd_attr.size_id = sizes.id WHERE prd_attr.product_id = $id");
        // dd($data_attr);
        return view('admin.product.edit', compact( 'list_feature_image','product','data_attr', 'list_colors', 'list_sizes', 'categories'));
    }

    function update(Request $request, $id){

        $discount = $request->input('discount');
        
        $arg_rules = [
            'title' =>  [
                'required',
                'max:255',
                Rule::unique('products')->ignore($id),
            ],
           
            'category_parent' => 'required',
            'slug' => 'max:255|required',
            'file'  => 'image|mimes:jpeg,png,jpg,gif,svg',
            'content'=> 'required',
            'price' => 'required|regex:/^[0-9]+$/',
            'colors' => 'required',
            // 'sizes'  => 'required',
            'quantity' => 'required',
            'description' => 'max:255'
        ];

        $arg_message = [
            'title.required' => 'Tiêu sản phẩm không được bỏ trống',
            'category_parent.required' => 'Danh mục không được bỏ trống',
            'slug.required' => 'Slug không được bỏ trống',
            'content.required' => 'Nội dung không được bỏ trống',
            'price.required' => 'Giá không được bỏ trống',
            'colors.required' => 'Màu không được bỏ trống',
            // 'sizes.required' => 'Màu không được bỏ trống',
            'quantity.required' => 'Số lượng không được bỏ trống',
            'image'     => 'Tệp tin tải lên phải có định dạng jpeg, png, jpg, gif, svg,webp',
            'price.regex' => 'Bạn chỉ được nhập số cho trường này',
            'title.unique' => 'Tên sản phẩm đã tồn tại',
            'max' => 'Độ dài lớn nhất là 255',
            'description'  => 'Mô tả ngắn',
        ];

        if(!empty($discount)){
            $arg_rules ['discount'] = 'regex:/^[0-9]{1,2}$/'; 
            $arg_message['discount.integer'] = 'Giảm giá phải là số';
            $arg_message['discount.size'] = 'Giảm giá có tối đa 2 số';
        }
         Validator::make($request->all(),$arg_rules, $arg_message)->validate();
       
        $input = $request->all();
        $input['thumbnail'] = Product::find($id)->thumbnail;
        $arr_image = [];
       
        if($request->hasFile('file')){
            $file = $request->file;
           
            $filename = $file->getClientOriginalName();
            $filename = time(). '-' .$filename;
            $file_size = $file->getSize();

            if($file_size > 5000000){
                return redirect("admin/product/edit/$id")->with('file_error', 'file upload vượt quá kích thước cho phép');
            }

            $file->move('public/uploads',  $filename );
            $thumbnail = 'public/uploads/' .  $filename;
            $input['thumbnail'] = $thumbnail;
        }

        if($request->hasFile('feature-image')){
            $file = $request->file('feature-image');
            foreach($file  as $item){
                $filename = $item->getClientOriginalName();
                $file_extention = $item->extension();
                $extension  = ['jpeg','png','jpg','gif','svg'];
                if(!in_array($file_extention, $extension)){
                    return redirect("admin/product/edit/$id")->with('file_error_extension', 'Tệp tin tải lên phải có định dạng jpeg, png, jpg, gif, svg');
                }
                $filename = time(). '-' .$filename;
                $item->move('public/uploads', $filename);
                $feature_image = 'public/uploads/' .$filename;
                $arr_image[] = $feature_image;
            }    
        }else{
            $feature_image =  Product::find($id)->feature_image;
            foreach($feature_image as $item){
                   $data_image = explode(',', $item->feature_image);
                   foreach($data_image as $value){
                    $arr_image[] =$value;
                   }
            }
        }

        if($request->input('category_parent') == '0'){
            return redirect('admin/product/add')->with('category_error', 'Bạn cần chọn danh mục sản phẩm');
        }
       
        
       
        $slug = $request->input('slug');
        $slug_current_id = Product::where('slug' , '=' , $slug)->where('id', '=', $id)->get();
        if(count($slug_current_id) > 0){
            $slug = $slug;
        }else{
            $slug_exit = Product::where('slug' , '=' , $slug)->get();
            if(count($slug_exit) > 0){
                $array = [1, 2, 3, 4, 5,6,7,8,9];
                $random = Arr::random($array);
                $slug = $slug. '-'.$random;
            }
        }
        if($request->input('product_hot')){
            $product_hot = $request->input('product_hot');
        }else{
            $product_hot = 0;
        }
      
        Product::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug' => $slug,
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'thumbnail' => $input['thumbnail'],
            'status' => $request->input('status'),
            'quantity' => $request->input('qty'),
            'product_hot' => $product_hot,
            'category_id' => $request->input('category_parent')
        ]);

        $input['feature_image'] = $arr_image;
        $item_image = implode(',',  $input['feature_image']);
        FeatureImage::where('product_id', $id)->update([
            'feature_image' =>$item_image, 
        ]);
        
        $color_id = $request->input('colors');
        $size_id = $request->input('sizes');
        $num_qty = $request->input('quantity');
        $product_attr= ProductAttribute::where('product_id', $id);
        $product_attr->delete();
        foreach ($color_id as $key => $value) {
            if(!empty($value)){
                $product_attr = new ProductAttribute;
                $product_attr->product_id = $id;
                $product_attr->color_id = $value;
                $product_attr->size_id = $size_id[$key];
                $product_attr->amount = $num_qty[$key];
                $product_attr->save();
            }
        }
        return redirect('admin/product/list')->with('status', 'Bạn đã cập nhật sản phẩm thành công');
    }

    function deleteFeatureImage($id){
        $product = Product::find($id);
        $arr_image =  $product->feature_image;
        dd($arr_image);
    }

    function delete($id){
        $category = Product::find($id);
        $category->delete();
        return response()->json([
            'status' => 1,
            'id' => $id,
            'message' => '<div class="alert alert-success">' . 'Bạn đã xóa bản ghi vào thùng rác thành công'. '</div>',
            'category' => $category
        ]);
        return response()->json($category);
    }

    function action(Request $request){
        $action = $request['infoAction'];
        $list_check = $request['id'];
        if($action == 'approve'){
           $categories =  Product::whereIn('id', $list_check)->update([
                 'status' => '1',
             ]);
         return response()->json([
            'status' => 1,
            'id' => $list_check,
            'message' => '<div class="alert alert-success">' .'Bạn đã phê duyệt bản ghi thành công' . '</div>', 
            'categories' => $categories
        ]);
        }else if($action == 'delete'){
            $categories =  Product::destroy($list_check);
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vào thùng rác thành công' . '</div>', 
                'categories' => $categories
            ]);
        }else if($action == 'restore'){
           
            $categories =  Product::withTrashed()->whereIn('id', $list_check)->restore();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã khôi phục bản ghi thành công' . '</div>', 
                'categories' => $categories
            ]);
        }else{
            $categories =  Product::whereIn('id', $list_check);
            $categories ->forceDelete();
            return response()->json([
                'status' => 1,
                'id' => $list_check,
                'message' => '<div class="alert alert-success">' .'Bạn đã xóa bản ghi vĩnh viễn thành công' . '</div>', 
                'categories' => $categories
            ]);
        }
        
     }

     function pagination(Request $request){
        if($request->ajax()){
            if(!$request['status']){
                $products = Product::where('status', '=', '1')->paginate(10);
            }else{
                if($request['status'] == 'pendding'){
                    $products = Product::where('status', '=', '0')->paginate(10);
                  }else if($request['status'] == 'active'){
                   $products = Product::where('status', '=', '1')->paginate(10);
                  }else{
                    $products = Product::onlyTrashed()->paginate(10);
                 }
            }
            $cate= ProductCategories::all();
            $data_qty = [];
            foreach($products as $item){
                $data_qty[] = $item->id;
            }
            $data_qty = implode(',',$data_qty);        
            $product_qty = '';
            if(!empty($data_qty)){
                $product_qty = DB::select("SELECT SUM(product_attribute.amount) as total_qty, product_attribute.product_id FROM products  INNER JOIN `product_attribute`  ON products.id = product_attribute.product_id  WHERE product_attribute.product_id IN ($data_qty)  GROUP BY products.id");
            }
            return view('admin.product.pagination',compact('products', 'product_qty' , 'cate'))->render();
        }
    }

     function search(Request $request){
        $keyword = '';
        if($request->input('keyword')){
            $keyword = $request->input('keyword');
            $products= Product::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }else{
            $products= Product::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
        }
            $data = ProductCategories::all();
            $object = new CategoryPostController();
            $categories= $object->data_tree($data);
            $data_qty = [];
            if(!empty($products)){
                foreach($products as $item){
                    if(!empty($item)){
                        $data_qty[] = $item->id;
                    }
                }
                $data_qty = implode(',',$data_qty);
                if(!empty($data_qty)){
                    $product_qty = DB::select("SELECT SUM(product_attribute.amount) as total_qty, product_attribute.product_id FROM `product_attribute` INNER JOIN products ON product_attribute.product_id = products.id WHERE product_attribute.product_id IN ($data_qty)  GROUP BY product_attribute.product_id");
                }
               
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
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">STT</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá tiền</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $count = 0;   
                            if($products->total() >0){
                                foreach ($products as $product){
                                    $count ++;   
                                ?>
                                    <tr data-id="<?php echo $product->id  ?>" class="element">
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="<?php echo $product->id  ?>">
                                        </td>
                                        <td scope="row"><?php echo $count; ?></td>
                                        <td><img src="<?php echo url($product->thumbnail) ?>" alt=""></td>
                                        <td><a href="<?php echo route('product.edit', $product->id) ?>"><?php echo $product->title; ?></a></td>
                                        <td><?php echo number_format($product->price,0,'','.') ?>đ</td>
                                        <td><?php 
                                               foreach ($product_qty as $qty){
                                                    if ($qty->product_id == $item->id){
                                                        echo $qty->total_qty;
                                                    }
                                                }
                                                     ?>
                                        </td>
                                        <?php
                                            
                                            foreach ($categories as $value){
                                                if($product->category_id == $value->id){
                                                    ?>
                                                     <td><?php echo str_repeat('- ', $value->level) . $value->title ?></td>
                                                <?php
                                                }
                                            }
                                            
                                        ?>
                                           
                                            <td><?php echo  $product->created_at ?></td>
                                            <td>
                                                <span class= "<?php 
                                                        foreach ($product_qty as $qty){
                                                            if ($qty->product_id == $item->id){
                                                                echo $qty->total_qty > 0 ? 'badge-success' : 'badge-danger';
                                                            }
                                                        }
                                                ?>">
                                                        <?php 
                                                       foreach ($product_qty as $qty){
                                                            if ($qty->product_id == $item->id){
                                                                echo $qty->total_qty > 0 ? 'Còn hàng' : 'Hết hàng';
                                                            }
                                                        }
                                                            
                                                        ?>
                                                </span>
                                               
                                            </td>
                                        <td>
                                            <?php 
                                                if(Gate::allows('product-edit')){
                                                    ?>
                                                    <a href="<?php echo route('product.edit', $product->id);?>" class=" btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                                <?php
                                                }
                                                if(Gate::allows('product-delete')){
                                                    ?>
                                                     <a href="<?php echo route('product.delete', $product->id);  ?>" data-id="<?php echo $product->id ?>" class="btn btn-danger btn-sm rounded-0 text-white delete-record" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
       echo $products->links();
    }

}
