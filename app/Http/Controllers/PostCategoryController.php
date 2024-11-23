<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    function index()
    {
        $post_cats = PostCategory::all();
        $post_cats = $this->data_tree($post_cats);
        return view('admin.post_category.index', compact('post_cats'));
    }

    function add(Request $request)
    {
        $request->validate(
            [
                'category_name' => 'required|unique:post_categories'
            ],
            [
                'required' => 'Tên danh mục không được để trống',
                'unique' => 'Tên danh mục đã tồn tại',
            ]
        );

        $post_cat = PostCategory::create(
            [
                'category_name' => $request->input('category_name'),
                'category_slug' => Str::slug($request->input('category_name')),
                'user_id' => Auth::id(),
                'parent_id' => $request->input('parent_id') ?? 0
            ]
        );

        if ($post_cat)
            return redirect(url('admin/post_category'))->with('add_status', 'Đã thêm thành công danh mục bài viết');
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

    function edit(PostCategory $postCategory)
    {
        $post_cats = PostCategory::all();
        $post_cats = $this->data_tree($post_cats);
        return view('admin.post_category.edit', compact('postCategory', 'post_cats'));
    }

    function update(Request $request, PostCategory $postCategory)
    {
        $request->validate(
            [
                'category_name' => 'required|unique:post_categories'
            ],
            [
                'required' => 'Tên danh mục không được để trống',
                'unique' => 'Tên danh mục đã tồn tại',
            ]
        );

        $postCategory->update(
            [
                'category_name' => $request->input('category_name'),
                'category_slug' => Str::slug($request->input('category_name')),
                'user_id' => Auth::id(),
                'parent_id' => $request->input('parent_id') ?? 0
            ]
        );

        return redirect(url('admin/post_category'))->with('edit_status', 'Đã cập nhật thành công danh mục bài viết');
    }

    function delete(PostCategory $postCategory)
    {
        $postCategory->delete();
        return redirect(url('admin/post_category'))->with('delete_status', 'Đã xóa thành công danh mục bài viết');
    }
}
