@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
           Cập nhật sản phẩm
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('product.update', $product->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group custom-title">
                            <label for="title">Tên sản phẩm</label>
                            <input class="form-control change-title" type="text" name="title" id="title" value="{{$product->title}}">
                            @error('title')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group custom-slug">
                            <label for="slug">slug</label>
                            <input class="form-control change-slug" type="text" name="slug" id="slug" value="{{$product->slug}}">
                            @error('slug')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price">Giá bán</label>
                            <input class="form-control" type="number" name="price" id="price" value="{{$product->price}}">
                            @error('price')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount">Số % giảm giá</label>
                                <input class="form-control" type="number" name="discount" id="discount" value="{{$product->discount}}">
                            @error('discount')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    
                    
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="description">Mô tả sản phẩm</label>
                            <textarea  class="form-control" name="description" id="description" cols="30" rows="12">{{$product->description}}</textarea>
                            @error('description')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                   
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Thêm thuộc tính:</label>
                            <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field">Thêm thuộc tính</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field_wrapper">
                            @foreach ($data_attr as $item)
                            <div class="append-item">
                                    <div class="row attr-item">
                                        <div class="col-lg-3 col-md-3">
                                            <label for="color">Màu sản phẩm</label>
                                            <select class="js-color-multiple form-control" name="colors[]">
                                                <option value="">Chọn màu sản phẩm</option>
                                                    @foreach ($list_colors as $color)
                                                        <option value="{{$color->id}}" {{$item->color_id == $color->id ? 'selected' : ''}}>{{$color->title}}</option>
                                                    @endforeach
                                            </select>
                                            @error('colors')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <label for="color">Size sản phẩm</label>
                                            <select class="js-size-multiple form-control" name="sizes[]" >
                                                <option value="">Chọn size sản phẩm</option>
                                                    @foreach ($list_sizes as $size)
                                                        <option value="{{$size->id}}" {{$item->size_id == $size->id ? 'selected' : ''}}>{{$size->name}}</option>
                                                    @endforeach
                                            </select>
                                            @error('sizes')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 mr-custom-attr">
                                                <label for="quantity">Số lượng</label>
                                                <input  type="number" min='1' name="quantity[]" id="quantity" value="{{$item->amount}}">
                                                @error('quantity')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 mr-top-attr">
                                            <a href="javascript:void(0);" class="remove_button btn btn-primary" title="Add field">Xóa thuộc tính</a>
                                        </div>
                                    </div>
                             </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">Chi tiết sản phẩm</label>
                    <textarea name="content" class="form-control content" name="content" id="content" cols="30" rows="8">{{$product->content}}</textarea>
                    @error('content')
                        <small class="text-danger">{{$message}}</small>    
                    @enderror
                </div>

                <div class="form-group thumb-product">
                    <label for="description">Ảnh đại diện </label>
                   <input type="file" id="change-image-product" name="file" class="form-control">
                   @if (!empty($product->thumbnail))
                     <img src="{{url($product->thumbnail)}}" alt="{{$product->title}}"  class="thumbnail feature-product">
                    @else
                    <img src="" alt="" class="thumbnail feature-product">
                   @endif
                    @error('file')
                        <small class="text-danger">{{$message}}</small>
                    @enderror

                    @if(session('file_error'))
                        <small class="text-danger">{{session('file_error')}}</small>
                    @endif
                </div>

                <div class="form-group thumbnail-feature-wrapper">
                    
                    <label for="description">Ảnh chi tiết </label>
                    <input type="file" id="feature-image" name="feature-image[]" class="form-control feature-image" value="" multiple>
                    <div class="feature-image-wrapper">
                        @if (!empty($list_feature_image))
                        @foreach ($list_feature_image as $item)
                            @foreach ($item as $key=> $values)
                                @if (!empty($values))
                                    <div class="remove-thumb">
                                        <img src="{{url($values)}}" alt="{{$product->title}}" class="thumbnail thumb-feature-product" data-key={{$key}} data-value={{$values}}>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        @endif
                    </div>
                    @error('feature-image')
                        <small class="text-danger">{{$message}}</small>
                    @enderror

                    @if(session('file_error_extension'))
                        <small class="text-danger">{{session('file_error_extension')}}</small>
                    @endif
                
                </div>

                <input type="hidden" name="qty" value="0">
                
                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" id="" name="category_parent">
                        <option value="0">Chọn danh mục</option>
                        @foreach ($categories as $item)
                            <option value="{{$item->id}}" {{$product->category_id == $item->id ? 'selected' : ''}}>{{str_repeat('-',$item->level).$item->title}}</option>    
                        @endforeach
                    </select>
                    @if(session('category_error'))
                        <small class="text-danger">{{session('category_error')}}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="product-hot">Sản phẩm nổi bật:</label>
                    <input type="checkbox" name="product_hot" id="product-hot" class="mr-left" value="1" {{$product->product_hot == '1' ? 'checked' : ''}}>
                    
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="0" {{$product->status == '0' ? 'checked' : ''}} >
                        <label class="form-check-label" for="exampleRadios1">
                            Chờ duyệt
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="1" {{$product->status == '1' ? 'checked' : ''}}>
                        <label class="form-check-label" for="exampleRadios2">
                            Công khai
                        </label>
                    </div>
                </div>



                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div> 
@endsection