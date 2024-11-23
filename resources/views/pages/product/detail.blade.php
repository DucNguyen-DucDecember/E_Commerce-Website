@extends('pages.layout')
@section('content')
<div id="main-content-wp" class="clearfix detail-product-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{url('/')}}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{route('product_category', $product->product_category->category_slug)}}"
                            title="">{{$product->product_category->category_name}}</a>
                    </li>
                    <li>
                        <a href="#" title="">{{$product->product_name}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="detail-product-wp">
                <div class="section-detail clearfix">
                    <div class="thumb-wp fl-left">
                        <a href="" title="" id="main-thumb">
                            <img id="zoom" src="{{url($product->product_thumb)}}" width="300"
                                data-zoom-image="{{url($product->product_thumb)}}" />
                        </a>
                        @if(!$product->product_image->isEmpty())
                            <div id="list-thumb">
                                @foreach ($product->product_image as $item)
                                    <a href="" data-image="{{url($item->image_name)}}"
                                        data-zoom-image="{{url($item->image_name)}}">
                                        <img id="zoom" src="{{url($item->image_name)}}" style="height: 80px !important;" />
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="thumb-respon-wp fl-left">
                        <img src="{{url($product->product_thumb)}}" alt="">
                    </div>
                    <div class="info fl-right">
                        <h3 class="product-name">{{$product->product_name}}</h3>
                        <div class="desc">
                            {{$product->product_desc}}
                        </div>
                        <div class="num-product">
                            <span class="title">Sản phẩm: </span>
                            <span class="status">Còn hàng</span>
                        </div>
                        <p class="price">{{number_format($product->product_price, 0, '.', '.')}}đ</p>
                        <form action="{{route('gio_hang_add', $product->id)}}" method="post">
                            @csrf
                            <div id="num-order-wp">
                                <a title="" id="minus"><i class="fa fa-minus"></i></a>
                                <input type="text" name="num-order" value="1" id="num-order">
                                <a title="" id="plus"><i class="fa fa-plus"></i></a>
                            </div>
                            <button type="submit" title="Thêm giỏ hàng" class="add-cart">Thêm giỏ hàng</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="section" id="post-product-wp">
                <div class="section-head">
                    <h3 class="section-title">Mô tả sản phẩm</h3>
                </div>
                <div class="section-detail">
                    <p>{!!$product->product_detail!!}</p>
                </div>
            </div>
            <div class="section" id="same-category-wp">
                <div class="section-head">
                    <h3 class="section-title">Cùng chuyên mục</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($related_pro as $item)
                            <li>
                                <a href="{{route('product_detail', $item->product_slug)}}" title="" class="thumb">
                                    <img src="{{url($item->product_thumb)}}">
                                </a>
                                <a href="" title="" class="product-name">{{$item->product_name}}</a>
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