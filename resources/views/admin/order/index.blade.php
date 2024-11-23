@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách đơn hàng</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        @if(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @endif
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlWithQuery(['status' => 'pending'])}}" class="text-primary">Đang chờ<span
                        class="text-muted">({{$number[0]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'processing'])}}" class="text-primary">Đang xử
                    lý<span class="text-muted">({{$number[1]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'shipped'])}}" class="text-primary">Đã ship<span
                        class="text-muted">({{$number[2]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'delivered'])}}" class="text-primary">Đã vận
                    chuyển<span class="text-muted">({{$number[3]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'canceled'])}}" class="text-primary">Hủy<span
                        class="text-muted">({{$number[4]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'delete'])}}" class="text-primary">Vô hiệu hóa<span
                        class="text-muted">({{$number[5]}})</span></a>
            </div>
            <form action="{{url('admin/order/act')}}" method="post">
                @csrf
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="list_act">
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
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$orders->isEmpty())
                            <?php    $n = 0 ?>
                            @foreach ($orders as $item)
                                <?php        $n++ ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="list_order[]" value="{{$item->id}}">
                                    </td>
                                    <th scope="row">{{$n}}</th>
                                    <th><a href="{{route('order_detail', $item->id)}}">{{$item->order_code}}</a></th>
                                    <td>{{$item->total_quantity}}</td>
                                    <td>{{number_format($item->total_amount, 0, '.', '.')}}₫</td>
                                    @if ($item->status == 'pending')
                                        <td><span class="badge badge-warning">Đang chờ</span></td>
                                    @elseif ($item->status == 'processing')
                                        <td><span class="badge badge-info">Đang xử lý</span></td>
                                    @elseif ($item->status == 'shipped')
                                        <td><span class="badge badge-primary">Đã ship</span></td>
                                    @elseif ($item->status == 'delivered')
                                        <td><span class="badge badge-success">Đã vận chuyển</span></td>
                                    @else
                                        <td><span class="badge badge-danger">Đã hủy</span></td>
                                    @endif
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <a href="{{route('order_detail', $item->id)}}"
                                            class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Detail Order"><i
                                                class="fas fa-info-circle"></i>
                                        </a>
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
                {{$orders->links()}}
            </nav>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-post').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('post_delete', ':id') }}`.replace(':id', itemId);

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