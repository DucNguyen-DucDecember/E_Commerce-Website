@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách vai trò</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        @if(session('add_status'))
            <small class="alert alert-success">{{session('add_status')}}</small>
        @elseif(session('edit_status'))
            <small class="alert alert-success">{{session('edit_status')}}</small>
        @elseif(session('delete_status'))
            <small class="alert alert-success">{{session('delete_status')}}</small>
        @endif
        <div class="card-body">
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="">
                    <option>Chọn</option>
                    <option>Tác vụ 1</option>
                    <option>Tác vụ 2</option>
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
                        <th scope="col">Vai trò</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 0 ?>
                    @foreach ($roles as $item)
                        <?php    $n++ ?>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td scope="row">{{$n}}</td>
                            <td><a href="">{{$item->name}}</a></td>
                            <td>{{$item->description}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>
                                <a href="{{route('role_edit', $item->id)}}" class="btn btn-success btn-sm rounded-0"
                                    type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                        class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm rounded-0 delete-role"
                                    type="button" data-toggle="tooltip" data-id="{{$item->id}}" data-placement="top"
                                    title="Delete"><i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                {{$roles->links()}}
            </nav>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-role').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('role_delete', ':id') }}`.replace(':id', itemId);

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