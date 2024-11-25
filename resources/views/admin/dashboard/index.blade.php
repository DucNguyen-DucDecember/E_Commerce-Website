@extends('admin.layout')
@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                <div class="card-body">
                    <h5 class="card-title">{{$number[0]}}</h5>
                    <p class="card-text">Đơn hàng giao dịch thành công</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐANG XỬ LÝ</div>
                <div class="card-body">
                    <h5 class="card-title">{{$number[1]}}</h5>
                    <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">DOANH SỐ</div>
                <div class="card-body">
                    <h5 class="card-title">{{number_format($number[3], 0, '.', '.')}}đ</h5>
                    <p class="card-text">Doanh số hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG HỦY</div>
                <div class="card-body">
                    <h5 class="card-title">{{$number[2]}}</h5>
                    <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end analytic  -->
    <div class="card">
        <div class="card-header font-weight-bold">
            ĐƠN HÀNG MỚI
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Mã</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Giá trị</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Thời gian</th>
                        @canany(['order.act', 'order.detail', 'order.view'])
                            <th scope="col">Tác vụ</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 0 ?>
                    @foreach ($orders as $item)
                        <?php    $n++ ?>
                        <tr>
                            <th scope="row">{{$n}}</th>
                            <th><a href="{{route('order_detail', $item->id)}}">{{$item->order_code}}</a></th>
                            <td>{{$item->total_quantity}}</td>
                            <td>{{number_format($item->total_amount, 0, '.', '.')}}₫</td>
                            @if ($item->status == 'pending')
                                <td><span class="badge badge-warning">Đang chờ</span></td>
                            @elseif ($item->status == 'processing')
                                <td><span class="badge badge-info">Đang xử lý</span></td>
                            @elseif ($item->status == 'shipped')
                                <td><span class="badge badge-primary">Đóng gói</span></td>
                            @elseif ($item->status == 'delivered')
                                <td><span class="badge badge-success">Đã nhận</span></td>
                            @else
                                <td><span class="badge badge-danger">Đã hủy</span></td>
                            @endif
                            <td>{{$item->created_at}}</td>
                            <td>
                                @canany(['order.act', 'order.detail', 'order.view'])
                                    <a href="{{route('order_detail', $item->id)}}"
                                        class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                            class="fas fa-info-circle"></i>
                                    </a>
                                @endcanany
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                {{$orders->links()}}
            </nav>
        </div>
    </div>

</div>
@endsection