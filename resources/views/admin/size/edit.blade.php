@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật size
        </div>
        <div class="card-body">
        <form method="POST" action="{{route('size.update', $size->id)}}">
            @csrf
                <div class="form-group">
                    <label for="title">Tên size</label>
                <input class="form-control" type="text" name="name" id="title" value="{{$size->name}}">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="0" {{$size->status == '0' ? 'checked' : ''}}>
                        <label class="form-check-label" for="exampleRadios1">
                          Chờ duyệt
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="1" {{$size->status == '1' ? 'checked' : ''}}>
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
