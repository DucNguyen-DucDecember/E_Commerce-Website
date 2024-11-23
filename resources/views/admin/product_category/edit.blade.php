@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Cập nhật danh mục sản phẩm
                </div>
                <div class="card-body">
                    <form action="{{route('product_category_update', $productCategory->id)}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input class="form-control" type="text" name="category_name" id="category_name"
                                value="{{$productCategory->category_name}}">
                            @error('category_name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Danh mục cha</label>
                            <select class="form-control" id="" name="parent_id">
                                <option value="0">Chọn danh mục</option>
                                @foreach ($product_cates as $item)
                                    <option value="{{$item->id}}">
                                        {{str_repeat('---|', $item->level) . $item->category_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection