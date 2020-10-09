<div class="change-color-size">
@if (count($product_size) >0)
<div class="product-size">
  <span>Chọn size:</span>
  <select name="product-size" id="size-item" class="size-info custom-select mr-sm-2">
      @foreach ($product_size as $size)
              <option value="{{$size->id}}" class="size-active">{{$size->name}}</option>
      @endforeach
  </select>
  <div class="error">
    @error('product-size')
    <small class="text-danger">{{$message}}</small>    
    @enderror
  </div>
</div>
@endif
</div>






