@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh mục sản phẩm
                </div>
                @if(session('add_status'))
                    <small class="alert alert-success">{{session('add_status')}}</small>
                @elseif(session('edit_status'))
                    <small class="alert alert-success">{{session('edit_status')}}</small>
                @elseif(session('delete_status'))
                    <small class="alert alert-success">{{session('delete_status')}}</small>
                @endif
                <div class="card-body">
                    <form action="{{url('admin/product_category/add')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input class="form-control" type="text" name="category_name" id="category_name"
                                value="{{old('category_name')}}">
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
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên danh mục</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $n = 0 ?>
                            @foreach ($product_cates as $item)
                                <tr>
                                    <?php    $n++ ?>
                                    <th scope="row">{{$n}}</th>
                                    <td>{{str_repeat('---|', $item->level) . $item->category_name}}</td>
                                    <td><a href="{{route('product_category_edit', $item->id)}}"
                                            class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0)"
                                            class="btn btn-danger btn-sm rounded-0 delete-product-category" type="button" data-id="{{$item->id}}"
                                            data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-product-category').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('product_category_delete', ':id') }}`.replace(':id', itemId);

                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect đến route xóa nếu người dùng xác nhận
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>
@endsection