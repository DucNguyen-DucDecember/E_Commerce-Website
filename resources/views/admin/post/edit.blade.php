@extends('admin.layout')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật bài viết
        </div>
        <div class="card-body">
            <form action="{{route('post_update', $post->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tiêu đề bài viết</label>
                    <input class="form-control" type="text" name="post_title" id="post_title"
                        value="{{$post->post_title}}">
                    @error('post_title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Miêu tả bài viết</label>
                    <textarea name="post_excerpt" class="form-control" id="content" cols="30"
                        rows="5">{!!$post->post_excerpt!!}</textarea>
                    @error('post_excerpt')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Nội dung bài viết</label>
                    <textarea name="post_content" class="form-control" id="post_content" cols="30"
                        rows="5">{!!$post->post_content!!}</textarea>
                    @error('post_content')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Ảnh bìa bài viết</label>
                    <input type="file" name="post_thumb">
                    @error('post_thumb')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" id="" name="category_id">
                        <option value="0">Chọn danh mục</option>
                        @foreach ($post_cates as $item)
                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="draft" value="draft">
                        <label class="form-check-label" for="draft">
                            Bản nháp
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="published"
                            value="published">
                        <label class="form-check-label" for="published">
                            Công khai
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="pending" value="pending">
                        <label class="form-check-label" for="pending">
                            Chờ duyệt
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="archieved"
                            value="archieved">
                        <label class="form-check-label" for="archieved">
                            Đã lưu
                        </label>
                    </div>
                    @error('post_status')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
<script>
    tinymce.init({
        selector: 'textarea#post_content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
@endsection