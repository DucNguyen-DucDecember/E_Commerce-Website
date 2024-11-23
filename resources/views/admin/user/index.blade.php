@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách thành viên</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" placeholder="Tìm kiếm" name="search">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        @if(session('add_status'))
            <small class="alert alert-success">{{session('add_status')}}</small>
        @elseif(session('status'))
            <small class="alert alert-success">{{session('status')}}</small>
        @elseif(session('edit_status'))
            <small class="alert alert-success">{{session('edit_status')}}</small>
         @elseif(session('delete_status'))
            <small class="alert alert-success">{{session('delete_status')}}</small>
        @endif
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlWithQuery(['status' => 'active'])}}" class="text-primary">Hoạt động<span
                        class="text-muted">({{$number[0]}})</span></a>
                <a href="{{request()->fullUrlWithQuery(['status' => 'delete'])}}" class="text-primary">Vô hiệu hóa<span
                        class="text-muted">({{$number[1]}})</span></a>
            </div>
            <form action="{{url('admin/user/act')}}" method="post">
                @csrf
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="act">
                        <option>Chọn</option>
                        @foreach ($list_act as $key => $act)
                            <option value="{{$key}}">{{$act}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Họ tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Quyền</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$users->isEmpty())
                            <?php    $n = 0 ?>
                            @foreach ($users as $item)
                                <?php        $n++ ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="list_id[]" value="{{$item->id}}">
                                    </td>
                                    <th scope="row">{{$n}}</th>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        @foreach ($item->role as $value)
                                            <span class="badge badge-info">{{$value->name}}</span>
                                        @endforeach
                                    </td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <a href="{{route('user_edit', $item->id)}}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @if($item->id != Auth::id())
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm rounded-0 text-white delete-user" type="button" data-id="{{$item->id}}"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        @endif
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
                {{$users->links()}}
            </nav>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-user').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('user_delete', ':id') }}`.replace(':id', itemId);

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