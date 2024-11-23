<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ProductCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
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

    function index()
    {
        $product_cates = ProductCategory::all();
        $product_cates = $this->data_tree($product_cates);
        return view('admin.product_category.index', compact('product_cates'));
    }

    function add(Request $request)
    {
        $request->validate(
            [
                'category_name' => 'required|unique:product_categories',
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên danh mục đã tồn tại',
            ]
        );

        $product_cate = ProductCategory::create(
            [
                'category_name' => $request->input('category_name'),
                'category_slug' => Str::slug($request->input('category_name')),
                'user_id' => Auth::id(),
                'parent_id' => $request->input('parent_id') ?? 0
            ]
        );

        if ($product_cate)
            return redirect(url('admin/product_category'))->with('add_status', 'Đã thêm thành công danh mục sản phẩm');
    }

    function edit(ProductCategory $productCategory)
    {
        $product_cates = ProductCategory::all();
        $product_cates = $this->data_tree($product_cates);
        return view('admin.product_category.edit', compact('productCategory', 'product_cates'));
    }

    function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate(
            [
                'category_name' => 'required|unique:product_categories',
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên danh mục đã tồn tại',
            ]
        );

        $productCategory->update(
            [
                'category_name' => $request->input('category_name'),
                'category_slug' => Str::slug($request->input('category_name')),
                'user_id' => Auth::id(),
                'parent_id' => $request->input('parent_id') ?? 0
            ]
        );

        return redirect(url('admin/product_category'))->with('edit_status', 'Đã cập nhật thành công danh mục sản phẩm');
    }

    function delete(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect(url('admin/product_category'))->with('delete_status', 'Đã xóa thành công danh mục sản phẩm');
    }
}
