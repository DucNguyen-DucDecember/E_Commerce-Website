@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật sản phẩm
        </div>
        <div class="card-body">
            <form action="{{route('product_update', $product->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="product_name" id="product_name"
                                value="{{$product->product_name}}">
                            @error('product_name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Giá</label>
                                    <input class="form-control" type="text" name="product_price" id="product_price"
                                        value="{{$product->product_price}}">
                                    @error('product_price')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Số lượng</label>
                                    <input class="form-control" type="text" name="stock_quantity" id="stock_quantity"
                                        value="{{$product->stock_quantity}}">
                                    @error('stock_quantity')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="intro">Chi tiết sản phẩm</label>
                    <textarea name="product_detail" class="form-control" id="product_detail" cols="30"
                        rows="5">{!!$product->product_detail!!}</textarea>
                    @error('product_detail')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="intro">Ảnh bìa sản phẩm</label>
                    <input type="file" name="product_thumb">
                    @error('product_thumb')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" id="" name="category_id">
                        <option value="0">Chọn danh mục</option>
                        @foreach ($product_cates as $item)
                            <option value="{{$item->id}}">
                                {{str_repeat('---|', $item->level) . $item->category_name}}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="product_status" id="active"
                                    value="active">
                                <label class="form-check-label" for="active">
                                    Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="product_status" id="inactive"
                                    value="inactive">
                                <label class="form-check-label" for="inactive">
                                    Inactive
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="product_status" id="out_of_stock"
                                    value="out_of_stock">
                                <label class="form-check-label" for="out_of_stock">
                                    Out_of_stock
                                </label>
                            </div>
                            @error('product_status')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Sản phẩm nổi bật</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_featured" id="yes" value="1">
                                <label class="form-check-label" for="yes">
                                    Có
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_featured" id="no" value="0">
                                <label class="form-check-label" for="no">
                                    Không
                                </label>
                            </div>
                            @error('is_featured')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
<script>
    tinymce.init({
        selector: 'textarea#product_detail',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
@endsection