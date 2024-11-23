<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class PostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }

    function has_child($data, $id)
    {
        foreach ($data as $v) {
            if ($v['parent_id'] == $id)
                return true;
        }
        return false;
    }

    function data_tree($data, $parent_id = 0, $level = 0)
    {
        $result = array();
        foreach ($data as $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $result[] = $v;
                if ($this->has_child($data, $v['id'])) {
                    $result_child = $this->data_tree($data, $v['id'], $level + 1);
                    $result = array_merge($result, $result_child);
                }
            }
        }
        return $result;
    }

    function index(Request $request)
    {
        $list_act = [
            'draft' => 'Bản nháp',
            'pending' => 'Chờ duyệt',
            'published' => 'Công khai',
            'archieved' => 'Đã lưu',
            'delete' => 'Vô hiệu hóa',
            'restore' => 'Khôi phục',
            'forceDelete' => 'Xóa vĩnh viễn'
        ];

        if ($request->status) {
            $status = $request->status;
            if ($status == 'draft') {
                unset($list_act['restore'], $list_act['forceDelete'], $list_act['draft']);
                $posts = Post::where('post_status', 'draft')->paginate(5);
            } elseif ($status == 'pending') {
                unset($list_act['restore'], $list_act['forceDelete'], $list_act['pending']);
                $posts = Post::where('post_status', 'pending')->paginate(5);
            } elseif ($status == 'published') {
                unset($list_act['restore'], $list_act['forceDelete'], $list_act['published']);
                $posts = Post::where('post_status', 'published')->paginate(5);
            } elseif ($status == 'archieved') {
                unset($list_act['restore'], $list_act['forceDelete'], $list_act['archieved']);
                $posts = Post::where('post_status', 'archieved')->paginate(5);
            } elseif ($status == 'delete') {
                unset($list_act['delete']);
                $posts = Post::onlyTrashed()->paginate(5);
            }
        } else {
            $search = $request->search ?? '';

            unset($list_act['restore'], $list_act['forceDelete']);

            $posts = Post::with('post_category')
                ->when($search, function ($query, $search) {
                    return $query->where('post_title', 'LIKE', "%{$search}%");
                })
                ->paginate(5);
        }

        $draft = Post::where('post_status', 'draft')->count();
        $pending = Post::where('post_status', 'pending')->count();
        $published = Post::where('post_status', 'published')->count();
        $archieved = Post::where('post_status', 'archieved')->count();
        $delete = Post::onlyTrashed()->count();

        $number = [$draft, $published, $pending, $archieved, $delete];

        return view('admin.post.index', compact('posts', 'number', 'list_act'));
    }

    function add()
    {
        $post_cates = PostCategory::all();
        $post_cates = $this->data_tree($post_cates);
        return view('admin.post.add', compact('post_cates'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'post_title' => 'required|unique:posts',
                'post_excerpt' => 'required',
                'post_content' => 'required',
                'post_status' => 'required',
                'post_thumb' => 'required',
                'category_id' => 'required|not_in:0'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên bài viết đã tồn tại',
                'not_in' => 'Chưa chọn danh mục'
            ],
            [
                'post_title' => 'Tên bài viết',
                'post_excerpt' => 'Mô tả ngắn',
                'post_content' => 'Nội dung',
                'post_status' => 'Trạng thái',
                'post_thumb' => 'Ảnh bìa',
                'category_id' => 'Danh mục bài viết'
            ]
        );

        if ($request->hasFile('post_thumb')) {
            $file = $request->file('post_thumb');
            $file_name = time() . '-' . $file->getClientOriginalName();
            $path = 'public/uploads/post_thumb/';
            $file->move($path, $file_name);

            $post = Post::create(
                [
                    'post_title' => $request->input('post_title'),
                    'post_slug' => Str::slug($request->input('post_title')),
                    'post_excerpt' => $request->input('post_excerpt'),
                    'post_content' => $request->input('post_content'),
                    'post_status' => $request->input('post_status'),
                    'post_thumb' => $path . $file_name,
                    'user_id' => Auth::id(),
                    'category_id' => $request->input('category_id')
                ]
            );
        }

        if ($post)
            return redirect(url('admin/post'))->with('add_status', 'Đã thêm thành công bài viết');
    }

    function act(Request $request)
    {
        $list_id = $request->list_id;

        if ($list_id) {
            if ($request->list_act == 'draft') {
                Post::whereIn('id', $list_id)->update(['post_status' => 'draft']);
                return redirect(url('admin/post'))->with('status', 'Đã cập nhật trạng thái thành bản nháp');
            } elseif ($request->list_act == 'pending') {
                Post::whereIn('id', $list_id)->update(['post_status' => 'pending']);
                return redirect(url('admin/post'))->with('status', 'Đã cập nhật trạng thái thành đang chờ');
            } elseif ($request->list_act == 'published') {
                Post::whereIn('id', $list_id)->update(['post_status' => 'published']);
                return redirect(url('admin/post'))->with('status', 'Đã cập nhật trạng thái thành công khai');
            } elseif ($request->list_act == 'archieved') {
                Post::whereIn('id', $list_id)->update(['post_status' => 'archieved']);
                return redirect(url('admin/post'))->with('status', 'Đã cập nhật trạng thái thành đã lưu');
            } elseif ($request->list_act == 'delete') {
                Post::whereIn('id', $list_id)->delete();
                return redirect(url('admin/post'))->with('status', 'Đã vô hiệu hóa bản ghi');
            } elseif ($request->list_act == 'restore') {
                Post::onlyTrashed()->restore();
                return redirect(url('admin/post'))->with('status', 'Đã khôi phục bản ghi');
            } elseif ($request->list_act == 'forceDelete') {
                Post::onlyTrashed()->forceDelete();
                return redirect(url('admin/post'))->with('status', 'Đã xóa vĩnh viễn bản ghi');
            } else
                return redirect(url('admin/post'))->with('status', 'Chọn tác vu để thực hiện');
        } else
            return redirect(url('admin/post'))->with('status', 'Chọn bản ghi để thực hiện');
    }

    function edit(Post $post)
    {
        $post_cates = PostCategory::all();
        $post_cates = $this->data_tree($post_cates);
        return view('admin.post.edit', compact('post_cates', 'post'));
    }

    function update(Request $request, Post $post)
    {
        $request->validate(
            [
                'post_title' => 'required|unique:posts',
                'post_excerpt' => 'required',
                'post_content' => 'required',
                'post_status' => 'required',
                'post_thumb' => 'required',
                'category_id' => 'required|not_in:0'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên bài viết đã tồn tại',
                'not_in' => 'Chưa chọn danh mục'
            ],
            [
                'post_title' => 'Tên bài viết',
                'post_excerpt' => 'Mô tả ngắn',
                'post_content' => 'Nội dung',
                'post_status' => 'Trạng thái',
                'post_thumb' => 'Ảnh bìa',
                'category_id' => 'Danh mục bài viết'
            ]
        );
        if ($request->hasFile('post_thumb')) {
            unlink($post->post_thumb);
            $file = $request->file('post_thumb');
            $file_name = time() . '-' . $file->getClientOriginalName();
            $path = 'public/uploads/post_thumb/';
            $file->move($path, $file_name);

            $post->update(
                [
                    'post_title' => $request->input('post_title'),
                    'post_slug' => Str::slug($request->input('post_title')),
                    'post_excerpt' => $request->input('post_excerpt'),
                    'post_content' => $request->input('post_content'),
                    'post_status' => $request->input('post_status'),
                    'post_thumb' => $path . $file_name,
                    'user_id' => Auth::id(),
                    'category_id' => $request->input('category_id')
                ]
            );
        }

        if ($post)
            return redirect(url('admin/post'))->with('update_status', 'Đã cập nhật thành công bài viết');
    }

    function delete(Post $post)
    {
        $post->delete();
        return redirect(url('admin/post'))->with('delete_status', 'Đã xóa thành công bài viết');
    }

    function bai_viet()
    {
        $best_sellers = Product::orderBy('stock_quantity', 'desc')->limit(10)->get();
        $posts = Post::paginate(5);
        return view('pages.post.bai_viet', compact('best_sellers', 'posts'));
    }

    function bai_viet_detail($post_slug)
    {
        $best_sellers = Product::orderBy('stock_quantity', 'desc')->limit(10)->get();
        $post = Post::where('post_slug', $post_slug)->first();
        return view('pages.post.detail', compact('best_sellers', 'post'));
    }
}
