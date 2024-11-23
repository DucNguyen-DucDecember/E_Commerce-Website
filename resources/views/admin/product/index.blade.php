@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách sản phẩm</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="text" class="form-control form-search" placeholder="Tìm kiếm" name="search">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        @if (session('add_status'))
            <small class="alert alert-success">{{session('add_status')}}</small>
        @elseif(session('update_status'))
            <small class="alert alert-success">{{session('update_status')}}</small>
        @elseif(session('delete_status'))
            <small class="alert alert-success">{{session('delete_status')}}</small>
        @elseif(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @endif
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlWithQuery(['status' => 'active'])}}" class="text-primary">active<span
                        class="text-muted">({{$number[0]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'inactive'])}}" class="text-primary">inactive<span
                        class="text-muted">({{$number[1]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'out_of_stock'])}}"
                    class="text-primary">out_of_stock<span class="text-muted">({{$number[2]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'delete'])}}" class="text-primary">vô hiệu hóa<span
                        class="text-muted">({{$number[3]}})</span></a>
            </div>
            <form action="{{url('admin/product/act')}}" method="post">
                @csrf
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="act">
                        <option>Chọn</option>
                        @foreach ($list_act as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$products->isEmpty())
                            <?php    $n = 0 ?>
                            @foreach ($products as $item)
                                <?php        $n++ ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="list_id[]" value="{{$item->id}}">
                                    </td>
                                    <td>{{$n}}</td>
                                    <td><img src="{{url($item->product_thumb)}}" alt="" style="width:120px"></td>
                                    <td><a href="{{route('product_edit', $item->id)}}">{{$item->product_name}}</a></td>
                                    <td>{{number_format($item->product_price, 0, '.', '.')}}₫</td>
                                    <td>{{$item->product_category->category_name}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td><span class="badge badge-success">{{$item->product_status}}</span></td>
                                    <td>
                                        <a href="{{route('product_edit', $item->id)}}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0)"
                                            class="btn btn-danger btn-sm rounded-0 text-white delete-product"
                                            data-id="{{$item->id}}" type="button" data-toggle="tooltip" data-placement="top"
                                            title="Delete"><i class="fa fa-trash"></i></a>
                                        <a href="{{route('product_ajax', $item->id)}}"
                                            class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fas fa-images"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">Không có bản ghi</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </form>
            <nav aria-label="Page navigation example">
                {{$products->links()}}
            </nav>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-product').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('product_delete', ':id') }}`.replace(':id', itemId);

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