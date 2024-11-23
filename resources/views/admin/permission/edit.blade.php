@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Cập nhật quyền
                </div>
                <div class="card-body">
                    <form action="{{route('permission_update', $permission_id->id)}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên quyền</label>
                            <input class="form-control" type="text" name="name" id="name"
                                value="{{$permission_id->name}}">
                            @error('name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <small class="form-text text-muted pb-2">Ví dụ: post.add</small>
                            <input class="form-control" type="text" name="slug" id="slug"
                                value="{{$permission_id->slug}}">
                            @error('slug')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" type="text" name="description"
                                id="description"> {{$permission_id->description}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection