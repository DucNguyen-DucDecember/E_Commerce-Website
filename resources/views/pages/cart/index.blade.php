@extends('pages.layout')
@section('content')
<div id="main-content-wp" class="cart-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{url('/')}}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="#" title="">Giỏ hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="info-cart-wp">
            <div class="section-detail table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Ảnh sản phẩm</td>
                            <td>Tên sản phẩm</td>
                            <td>Giá sản phẩm</td>
                            <td>Số lượng</td>
                            <td colspan="2">Thành tiền</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0 ?>
                        <form>
                            @csrf
                            @foreach (Cart::content() as $item)
                                <?php    $n++ ?>
                                <tr>
                                    <td>{{$n}}</td>
                                    <td>
                                        <a href="" title="" class="thumb">
                                            <img src="{{$item->options->product_thumb}}" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{url('san-pham/' . $item->options->product_slug . '.html')}}" title=""
                                            class="name-product">{{$item->name}}</a>
                                    </td>
                                    <td>{{number_format($item->price, 0, '.', '.')}}đ</td>
                                    <td>
                                        <span class="icon-minus" data-rowid="{{$item->rowId}}" data-id="{{$item->id}}">
                                            <i class="fa fa-minus"></i>
                                        </span>
                                        <input type="text" data-rowid="{{$item->rowId}}" name="num-order"
                                            value="{{$item->qty}}" class="num-order">
                                        <span class="icon-plus" data-rowid="{{$item->rowId}}" data-id="{{$item->id}}">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                    </td>
                                    <td data-rowid="{{$item->rowId}}">{{number_format($item->total, 0, '.', '.')}}đ</td>
                                    <td>
                                        <a href="javascript:void(0)" title=""
                                            class="del-product delete-cart-item" data-rowid="{{$item->rowId}}"><i
                                                class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <p id="total-price" class="fl-right">Tổng giá: <span
                                            class="total">{{Cart::total()}}đ</span></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <div class="fl-right">
                                        <a href="{{url('thanh-toan')}}" title="" id="checkout-cart">Thanh toán</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="section" id="action-cart-wp">
            <div class="section-detail">
                <a href="{{url('/')}}" title="" id="buy-more">Mua tiếp</a><br />
                <a href="javascript:void(0)" class="cart-destroy" title="" id="delete-cart">Xóa giỏ hàng</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('span.icon-minus').click(function () {
            var qty = $(this).siblings('input.num-order').val();
            var id = $(this).data('id');
            var rowId = $(this).data('rowid');
            var _token = $('input[name="_token"]').val();

            if (qty > 1)
                qty--;
            $('input[data-rowid="' + rowId + '"]').val(qty);

            $.ajax({
                url: "{{url('gio-hang/update')}}",
                method: 'post',
                data: { rowId: rowId, qty: qty, _token: _token, id: id },
                dataType: 'json',
                success: function (response) {
                    $('td[data-rowid="' + rowId + '"]').html(response.sub_total + 'đ');
                    $('span.total').html(response.total + 'đ');
                    $('span.cart-number').html(response.number);
                    $('span[data-rowid="' + rowId + '"].item-qty').html(qty);
                    $('p.price.fl-right').html(response.total + 'Đ');
                }
            });
        });

        $('span.icon-plus').click(function () {
            var qty = $(this).siblings('input.num-order').val();
            var id = $(this).data('id');
            var rowId = $(this).data('rowid');
            var _token = $('input[name="_token"]').val();

            qty++
            $('input[data-rowid="' + rowId + '"]').val(qty);

            $.ajax({
                url: "{{url('gio-hang/update')}}",
                method: 'post',
                data: { rowId: rowId, qty: qty, _token: _token, id: id },
                dataType: 'json',
                success: function (response) {
                    $('td[data-rowid="' + rowId + '"]').html(response.sub_total + 'đ');
                    $('span.total').html(response.total + 'đ');
                    $('span.cart-number').html(response.number);
                    $('span[data-rowid="' + rowId + '"].item-qty').html(qty);
                    $('p.price.fl-right').html(response.total + 'Đ');
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-cart-item').forEach(function (button) {
            button.addEventListener('click', function () {
                var rowId = this.getAttribute('data-rowid');
                var deleteUrl = `{{ route('gio_hang_remove', ':rowId') }}`.replace(':rowId', rowId);

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

        document.querySelectorAll('.cart-destroy').forEach(function (button) {
            button.addEventListener('click', function () {
                var deleteUrl = `{{ url('gio-hang/delete') }}`;

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