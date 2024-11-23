@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách bài viết</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="text" class="form-control form-search" placeholder="Tìm kiếm" name="search">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        @if(session('add_status'))
            <small class="alert alert-success">{{session('add_status')}}</small>
        @elseif(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @elseif(session('update_status'))
            <small class="alert alert-success">{{session('update_status')}}</small>
        @elseif(session('delete_status'))
            <small class="alert alert-success">{{session('delete_status')}}</small>
        @endif
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlWithQuery(['status' => 'draft'])}}" class="text-primary">Bản nháp<span
                        class="text-muted">({{$number[0]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'published'])}}" class="text-primary">Công khai<span
                        class="text-muted">({{$number[1]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'pending'])}}" class="text-primary">Chờ duyệt<span
                        class="text-muted">({{$number[2]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'archieved'])}}" class="text-primary">Đã lưu<span
                        class="text-muted">({{$number[3]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'delete'])}}" class="text-primary">Vô hiệu hóa<span
                        class="text-muted">({{$number[4]}})</span></a>
            </div>
            <form action="{{url('admin/post/act')}}" method="post">
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
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$posts->isEmpty())
                            <?php    $n = 0 ?>
                            @foreach ($posts as $item)
                                <?php        $n++ ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="list_id[]" value="{{$item->id}}">
                                    </td>
                                    <td scope="row">{{$n}}</td>
                                    <td><img src="{{url($item->post_thumb)}}" alt="" style="width: 120px;"></td>
                                    <td><a href="{{route('post_edit', $item->id)}}">{{$item->post_title}}</a>
                                    </td>
                                    <td>{{$item->post_category->category_name}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td><a href="{{route('post_edit', $item->id)}}" class="btn btn-success btn-sm rounded-0"
                                            type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm rounded-0 delete-post"
                                            data-id="{{$item->id}}" type="button" data-toggle="tooltip" data-placement="top"
                                            title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    Không có bản ghi
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </form>
            <nav aria-label="Page navigation example">
                {{$posts->links()}}
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