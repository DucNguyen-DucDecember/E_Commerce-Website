@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật người dùng
        </div>
        <div class="card-body">
            <form action="{{route('user_update', $user->id)}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}">
                    @error('name')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" readonly>
                </div>
                <!-- <div class="form-group">
                    <label for="email">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('name')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div> -->
                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control" id="" multiple name="roles[]">
                        @foreach ($roles as $role)
                            <option value="{{$role->id}}" {{in_array($role->id, $selected_roles) ? 'selected' : ''}}>
                                {{$role->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection