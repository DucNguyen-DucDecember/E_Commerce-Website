@extends('pages.layout')
@section('content')
<div id="main-content-wp" class="clearfix blog-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="{{url('/')}}" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="#" title="">Blog</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title">Blog</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @foreach ($posts as $item)
                            <li class="clearfix">
                                <a href="{{route('bai_viet_detail', $item->post_slug)}}" title="" class="thumb fl-left">
                                    <img src="{{$item->post_thumb}}" alt="">
                                </a>
                                <div class="info fl-right">
                                    <a href="{{route('bai_viet_detail', $item->post_slug)}}" title=""
                                        class="title">{{$item->post_title}}</a>
                                    <span class="create-date">{{$item->created_at}}</span>
                                    <p class="desc">{{$item->post_excerpt}}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="section" id="paging-wp">
                <div class="section-detail">
                    {{$posts->links()}}
                </div>
            </div>
        </div>
        <div class="sidebar fl-left">
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
                    <a href="?page=detail_blog_product" title="" class="thumb">
                        <img src="{{url('public/pages/images/banner.png')}}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection