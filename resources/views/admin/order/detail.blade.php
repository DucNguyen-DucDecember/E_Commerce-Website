@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Thông tin đơn hàng</h5>
        </div>
        @if(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @endif
        <div class="card-body">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">Mã</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Địa chỉ nhận hàng</th>
                        <th scope="col">Tình trạng</th>
                        <th scope="col">Phương thức thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{$order_id->order_code}}</th>
                        <th>{{$customer->customer->fullname}}</th>
                        <th>{{$customer->customer->address}}</th>
                        <td>
                            <form action="{{url('admin/order/update')}}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order_id->id}}">
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option value="pending" {{$order_id->status == 'pending' ? 'selected' : ''}}>Đang chờ
                                    </option>
                                    <option value="processing" {{$order_id->status == 'processing' ? 'selected' : ''}}>
                                        Đang xử
                                        lý</option>
                                    <option value="shipped" {{$order_id->status == 'shipped' ? 'selected' : ''}}>Đang ship
                                    </option>
                                    <option value="delivered" {{$order_id->status == 'delivered' ? 'selected' : ''}}>Đang
                                        vận
                                        chuyển</option>
                                    <option value="canceled" {{$order_id->status == 'canceled' ? 'selected' : ''}}>Đã hủy
                                    </option>
                                </select>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </td>
                        @if ($order_id->status == 'COD')
                            <th>Thanh toán khi nhận hàng</th>
                        @else
                            <th>Thanh toán online</th>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Chi tiết đơn hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ảnh sản phẩm</th>
                        <th scope="col">Tên Sản phẩm</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 0 ?>
                    @foreach ($order_items as $product)
                        <?php    $n++ ?>
                        <tr>
                            <th>{{$n}}</th>
                            <th><img src="{{url($product->product->product_thumb)}}" width="100" alt=""></th>
                            <th>{{$product->product->product_name}}</th>
                            <th>{{number_format($product->product_price, 0, '.', '.')}}đ</th>
                            <th>{{$product->product_quantity}}</th>
                            <th>{{number_format($product->product_price * $product->product_quantity, 0, '.', '.')}}đ</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Giá trị đơn hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">Tổng số lượng</th>
                        <th scope="col">Tổng đơn hàng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$order_id->total_quantity}}</td>
                        <td>{{number_format($order_id->total_amount, 0, '.', '.')}}đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection