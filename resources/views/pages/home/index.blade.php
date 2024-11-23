@extends('pages.layout')
@section('content')
<div id="main-content-wp" class="home-page clearfix">
    <div class="wp-inner">
        <div class="main-content fl-right">
            <div class="section" id="slider-wp">
                <div class="section-detail">
                    <div class="item">
                        <img src="{{url('public/pages/images/slider-01.png')}}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{url('public/pages/images/slider-02.png')}}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{url('public/pages/images/slider-03.png')}}" alt="">
                    </div>
                </div>
            </div>
            <div class="section" id="support-wp">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <div class="thumb">
                                <img src="{{url('public/pages/images/icon-1.png')}}">
                            </div>
                            <h3 class="title">Miễn phí vận chuyển</h3>
                            <p class="desc">Tới tận tay khách hàng</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{url('public/pages/images/icon-2.png')}}">
                            </div>
                            <h3 class="title">Tư vấn 24/7</h3>
                            <p class="desc">1900.9999</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{url('public/pages/images/icon-3.png')}}">
                            </div>
                            <h3 class="title">Tiết kiệm hơn</h3>
                            <p class="desc">Với nhiều ưu đãi cực lớn</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{url('public/pages/images/icon-4.png')}}">
                            </div>
                            <h3 class="title">Thanh toán nhanh</h3>
                            <p class="desc">Hỗ trợ nhiều hình thức</p>
                        </li>
                        <li>
                            <div class="thumb">
                                <img src="{{url('public/pages/images/icon-5.png')}}">
                            </div>
                            <h3 class="title">Đặt hàng online</h3>
                            <p class="desc">Thao tác đơn giản</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="section" id="feature-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm nổi bật</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($featured as $item)
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
            @foreach ($categories as $product_category)
                <div class="section" id="list-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">{{$product_category->category_name}}</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            @foreach ($product_category->product as $item)
                                <li>
                                    <a href="{{route('product_detail', $item->product_slug)}}" title="" class="thumb">
                                        <img src="{{url($item->product_thumb)}}" alt="">
                                    </a>
                                    <a href="{{route('product_detail', $item->product_slug)}}" title=""
                                        class="product-name">{{$item->product_name}}</a>
                                    <div class="price">
                                        <span class="new">{{number_format($item->product_price, 0, '.', '.')}}đ</span>
                                    </div>
                                    <div class="action clearfix">
                                        <a href="" data-id="{{$item->id}}" title="Thêm giỏ hàng" class="add-cart fl-left">Thêm
                                            giỏ hàng</a>
                                        <a href="{{route('mua_ngay', $item->product_slug)}}" title="Mua ngay"
                                            class="buy-now fl-right">Mua ngay</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
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
            <div class="section" id="selling-wp">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm bán chạy</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($best_sellers as $item)
                            <li class="clearfix">
                                <a href="{{route('product_detail', $item->product_slug)}}" title="" class="thumb fl-left">
                                    <img src="{{url($item->product_thumb)}}" alt="">
                                </a>
                                <div class="info fl-right">
                                    <a href="{{route('product_detail', $item->product_slug)}}" title=""
                                        class="product-name">{{$item->product_name}}</a>
                                    <div class="price">
                                        <span class="new">{{number_format($item->product_price, 0, '.', '.')}}đ</span>
                                    </div>
                                    <a href="{{route('mua_ngay', $item->product_slug)}}" title="" class="buy-now">Mua
                                        ngay</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="banner-wp">
                <div class="section-detail">
                    <a href="" title="" class="thumb">
                        <img src="{{url('public/pages/images/banner.png')}}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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