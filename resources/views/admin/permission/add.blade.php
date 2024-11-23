@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm quyền
                </div>
                @if(session('add_status'))
                    <small class="alert alert-success">{{session('add_status')}}</small>
                @elseif(session('edit_status'))
                    <small class="alert alert-success">{{session('edit_status')}}</small>
                @elseif(session('delete_status'))
                    <small class="alert alert-success">{{session('delete_status')}}</small>
                @endif
                <div class="card-body">
                    <form action="{{url('admin/permission/store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên quyền</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                            @error('name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <small class="form-text text-muted pb-2">Ví dụ: post.add</small>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{old(key: 'slug')}}">
                            @error('slug')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" type="text" name="description" id="description"> </textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách quyền
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên quyền</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_permission as $module_key => $module_value)
                                <tr>
                                    <td></td>
                                    <td><strong>{{ucfirst($module_key)}}</strong></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php    $n = 0 ?>
                                @foreach ($module_value as $item)
                                    <?php        $n++ ?>
                                    <tr>
                                        <td scope="row">{{$n}}</td>
                                        <td>|---{{$item->name}}</td>
                                        <td>{{$item->slug}}</td>
                                        <td>
                                            @can('permission.edit')
                                            <a href="{{route('permission_edit', $item->id)}}"
                                                class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Edit"><i class="fa fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('permission.delete')
                                                <a href="javascript:void(0)" class="btn btn-danger btn-sm rounded-0 delete-permission" type="button" data-id="{{$item->id}}"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-permission').forEach(function (button) {
            button.addEventListener('click', function () {
                var itemId = this.getAttribute('data-id');
                var deleteUrl = `{{ route('permission_delete', ':id') }}`.replace(':id', itemId);

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