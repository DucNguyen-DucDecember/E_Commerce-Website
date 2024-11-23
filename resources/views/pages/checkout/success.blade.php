@extends('pages.layout')
@section('content')
<div class="container text-center">
    <div class="row ">
        <div class="col-12 ">
            <div class="thank-you-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h1 class="display-4" style="color: red;">Thank You!</h1>
            <p class="lead">Cảm ơn bạn đã đặt hàng tại Badminton_shop</p>
            <hr class="mt-2">
            <p class="mb-4">Đơn hàng đã được gửi đến email của bạn, vui lòng theo dõi hành trình giao hàng nhé!</p>
            <a class="btn btn-lg text-white" href="{{url('/')}}" role="button" style="background: red">Tiếp Tục Mua
                Sắm</a>
        </div>
    </div>
</div>
@endsection