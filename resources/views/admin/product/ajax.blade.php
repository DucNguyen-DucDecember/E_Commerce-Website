@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm hình ảnh cho sản phẩm
        </div>
        @if(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @endif
        <form action="{{route('ajax_upload', $product->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <input type="file" class="form-control" name="list_image[]" multiple accept="image/*">
                    <div class="error"></div>
                    @error('list_image')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-3">
                    <input type="submit" name="upload" value="Tải ảnh" class="btn btn-success">
                </div>
            </div>
        </form>
        <div class="card-body">
            <input type="hidden" value="{{$product->id}}" name="product_id" class="product_id">
            <form action="">
                @csrf
                <div id="result">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function () {
        load_image();

        function load_image() {
            var product_id = $('input.product_id').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{url('admin/ajax/select')}}",
                method: 'post',
                data: { product_id: product_id, _token: _token },
                success: function (response) {
                    $('#result').html(response);
                }
            })
        }
    });

    $(document).on('click', '.delete-ajax-product', function () {

        function load_image() {
            var product_id = $('input.product_id').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{url('admin/ajax/select')}}",
                method: 'post',
                data: { product_id: product_id, _token: _token },
                success: function (response) {
                    $('#result').html(response);
                }
            })
        }

        var product_img_Id = $(this).data('id');
        var _token = $('input[name="_token"]').val();

        // Sử dụng SweetAlert để hiển thị xác nhận xóa
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            text: "Hành động này sẽ không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu AJAX xóa hình ảnh
                $.ajax({
                    url: "{{ url('admin/ajax/delete') }}", // Đảm bảo route này tồn tại trong Laravel
                    method: 'POST',
                    data: {
                        product_img_Id: product_img_Id,
                        _token: _token
                    },
                    success: function (response) {
                        load_image(); // Tải lại danh sách ảnh (nếu có hàm load_image)

                        // Hiển thị thông báo thành công bằng SweetAlert
                        Swal.fire(
                            'Đã xóa!',
                            'Hình ảnh đã được xóa thành công.',
                            'success'
                        );
                    },
                    error: function () {
                        // Hiển thị thông báo lỗi nếu xóa thất bại
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xóa hình ảnh.',
                            'error'
                        );
                    }
                });
            }
        });
    });

</script>
@endsection