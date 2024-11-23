@extends('pages.layout')
@section('content')
<div id="main-content-wp" class="clearfix category-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{url('/')}}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Tìm kiếm</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-product-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title fl-left">Tìm kiếm</h3>
                    <div class="filter-wp fl-right">
                        <div class="form-filter">
                            <form action="#">
                                <select name="order_product">
                                    <option value="0">Sắp xếp</option>
                                    <option value="1">Từ A-Z</option>
                                    <option value="2">Từ Z-A</option>
                                    <option value="3">Giá cao xuống thấp</option>
                                    <option value="4">Giá thấp lên cao</option>
                                </select>
                                <button type="submit">Lọc</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="section-detail">
                    <ul class="list-item clearfix" id="result">
                        @foreach ($products as $item)
                            <li>
                                <a href="{{route('product_detail', $item->product_slug)}}" title="" class="thumb">
                                    <img src="{{url($item->product_thumb)}}">
                                </a>
                                <a href="{{route('product_detail', $item->product_slug)}}" title=""
                                    class="product-name">{{$item->product_name}}</a>
                                <div class="price">
                                    <span class="new">{{number_format($item->product_price, 0, '.', '.')}}đ</span>
                                </div>
                                <div class="action clearfix">
                                    <a href="" type="button" title="Thêm giỏ hàng" class="add-cart fl-left"
                                        data-id="{{$item->id}}">Thêm
                                        giỏ hàng</a>
                                    <a href="{{route('mua_ngay', $item->product_slug)}}" title="Mua ngay"
                                        class="buy-now fl-right">Mua ngay</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail">
                    {{$products->links()}}
                </div>
            </div>
        </div>
        <div class="sidebar fl-left">
            <div class="section" id="category-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Danh mục sản phẩm</h3>
                </div>
                <div class="secion-detail">
                    {!!$pro_cates!!}
                </div>
            </div>
            <div class="section" id="banner-wp">
                <div class="section-detail">
                    <a href="?page=detail_product" title="" class="thumb">
                        <img src="public/images/banner.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.filt_price', function () {
        var filt_price = $(this).val();
        var category_id = $(this).data('category-id');
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{route('ajax_filt_price')}}",
            method: 'post',
            data: { filt_price: filt_price, category_id: category_id, _token: _token },
            success: function (response) {
                $('ul#result').html(response);
            }
        })
    });

    $(document).on('click', 'a.add-cart.fl-left', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{url('ajax/add_cart')}}",
            method: 'post',
            data: { id: id, _token: _token },
            dataType: 'json',
            success: function (response) {
                Swal.fire({
                    title: "Chúc mừng",
                    text: "Bạn đã thêm thành công sản phẩm vào giỏ hàng",
                    icon: "success",
                    confirmButtonText: "Xác nhận"
                });
                $('span.cart-number').html(response.number);
                $('ul.list-cart').html(response.list_item);
                $('p.price.fl-right').html(response.total + 'Đ');
            }
        })
    })
</script>
@endsection