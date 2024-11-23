<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }

    function index(Request $request)
    {
        $list_act = ['active' => 'active', 'inactive' => 'inactive', 'out_of_stock' => 'out_of_stock', 'delete' => 'vô hiệu hóa', 'restore' => 'Khôi phục', 'forceDelete' => 'Xóa vĩnh viễn'];

        $search = '';

        if ($request->status) {
            $status = $request->status;
            if ($status == 'active') {
                unset($list_act['active'], $list_act['restore'], $list_act['forceDelete']);
                $products = Product::where('product_status', 'active')->paginate(5);
            } elseif ($status == 'inactive') {
                unset($list_act['inactive'], $list_act['restore'], $list_act['forceDelete']);
                $products = Product::where('product_status', 'inactive')->paginate(5);
            } elseif ($status == 'out_of_stock') {
                unset($list_act['out_of_stock'], $list_act['restore'], $list_act['forceDelete']);
                $products = Product::where('product_status', 'out_of_stock')->paginate(5);
            } elseif ($status == 'delete') {
                unset($list_act['delete'], $list_act['active'], $list_act['inactive'], $list_act['out_of_stock']);
                $products = Product::onlyTrashed()->paginate(5);
            }
        } else {
            unset($list_act['restore'], $list_act['forceDelete']);
            if ($request->search) {
                $search = $request->search;
                $products = Product::with('product_category')->where('product_status', 'active')->where('product_name', 'LIKE', "%{$search}%")->paginate(5);
            }
            $products = Product::with('product_category')->where('product_status', 'active')->where('product_name', 'LIKE', "%{$search}%")->paginate(5);
        }

        $active = Product::where('product_status', 'active')->count();
        $inactive = Product::where('product_status', 'inactive')->count();
        $out_of_stock = Product::where('product_status', 'out_of_stock')->count();
        $delete = Product::onlyTrashed()->count();
        $number = [$active, $inactive, $out_of_stock, $delete];

        return view('admin.product.index', compact('products', 'number', 'list_act'));
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

    function add()
    {
        $product_cates = ProductCategory::all();
        $product_cates = $this->data_tree($product_cates);
        return view('admin.product.add', compact('product_cates'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'product_name' => 'required|unique:products',
                'product_price' => 'required|integer',
                'stock_quantity' => 'required|integer',
                'product_detail' => 'required',
                'category_id' => 'required|not_in:0',
                'product_status' => 'required',
                'is_featured' => 'required',
                'product_thumb' => 'required|image'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại',
                'integer' => ':attribute phải là dạng số',
                'not_in' => 'Chưa chọn danh mục',
                'image' => 'File tải lên phải là hình ảnh'
            ],
            [
                'product_name' => 'Tên sản phẩm',
                'product_price' => 'Giá sản phẩm',
                'stock_quantity' => 'Số lượng trong kho',
                'product_detail' => 'Chi tiết sản phẩm',
                'category_id' => 'Danh mục sản phẩm',
                'product_status' => 'Trạng thái sản phẩm',
                'is_featured' => 'Sản phẩm nỗi bật',
                'product_thumb' => 'Ảnh bìa sản phẩm'
            ]
        );

        if ($request->hasFile('product_thumb')) {
            $file = $request->file('product_thumb');
            $file_name = time() . '-' . $file->getClientOriginalName();
            $path = 'public/uploads/product_thumb/';

            $file->move($path, $file_name);

            $product = Product::create(
                [
                    'product_name' => $request->input('product_name'),
                    'product_slug' => str::slug($request->input('product_name')),
                    'product_price' => $request->input('product_price'),
                    'product_desc' => $request->input('product_desc'),
                    'stock_quantity' => $request->input('stock_quantity'),
                    'product_detail' => $request->input('product_detail'),
                    'category_id' => $request->input('category_id'),
                    'product_status' => $request->input('product_status'),
                    'user_id' => Auth::id(),
                    'product_thumb' => $path . $file_name,
                    'is_featured' => $request->input('is_featured')
                ]
            );
        }

        if ($product)
            return redirect(url('admin/product'))->with('add_status', 'Đã thêm thành công sản phẩm');
    }

    function edit(Product $product)
    {
        $product_cates = ProductCategory::all();
        $product_cates = $this->data_tree($product_cates);
        return view('admin.product.edit', compact('product', 'product_cates'));
    }

    function update(Request $request, Product $product)
    {
        $request->validate(
            [
                'product_name' => 'required|unique:products',
                'product_price' => 'required|integer',
                'stock_quantity' => 'required|integer',
                'product_detail' => 'required',
                'category_id' => 'required|not_in:0',
                'product_status' => 'required',
                'is_featured' => 'required',
                'product_thumb' => 'required|image'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại',
                'integer' => ':attribute phải là dạng số',
                'not_in' => 'Chưa chọn danh mục',
                'image' => 'File tải lên phải là hình ảnh'
            ],
            [
                'product_name' => 'Tên sản phẩm',
                'product_price' => 'Giá sản phẩm',
                'stock_quantity' => 'Số lượng trong kho',
                'product_detail' => 'Chi tiết sản phẩm',
                'category_id' => 'Danh mục sản phẩm',
                'product_status' => 'Trạng thái sản phẩm',
                'is_featured' => 'Sản phẩm nỗi bật',
                'product_thumb' => 'Ảnh bìa sản phẩm'
            ]
        );

        if ($request->hasFile('product_thumb')) {
            unlink($product->product_thumb);

            $file = $request->file('product_thumb');
            $file_name = time() . '-' . $file->getClientOriginalName();
            $path = 'public/uploads/product_thumb/';
            $file->move($path, $file_name);

            $product->update(
                [
                    'product_name' => $request->input('product_name'),
                    'product_slug' => str::slug($request->input('product_name')),
                    'product_price' => $request->input('product_price'),
                    'stock_quantity' => $request->input('stock_quantity'),
                    'product_detail' => $request->input('product_detail'),
                    'category_id' => $request->input('category_id'),
                    'product_status' => $request->input('product_status'),
                    'user_id' => Auth::id(),
                    'product_thumb' => $path . $file_name,
                    'is_featured' => $request->input('is_featured')
                ]
            );
        }

        return redirect(url('admin/product'))->with('update_status', 'Đã cập nhật thành công sản phẩm');
    }

    function delete(Product $product)
    {
        $product->delete();
        return redirect(url('admin/product'))->with('delete_status', 'Đã xóa thành công sản phẩm');
    }

    function act(Request $request)
    {
        $list_id = $request->list_id;

        if ($list_id) {
            $act = $request->act;

            if ($act == 'active') {
                Product::whereIn('id', $list_id)->update(['product_status' => 'active']);
                return redirect(url('admin/product'))->with('status', 'Đã cập nhật thành công bản ghi thành active');
            } elseif ($act == 'inactive') {
                Product::whereIn('id', $list_id)->update(['product_status' => 'inactive']);
                return redirect(url('admin/product'))->with('status', 'Đã cập nhật thành công bản ghi thành inactive');
            } elseif ($act == 'out_of_stock') {
                Product::whereIn('id', $list_id)->update(['product_status' => 'out_of_stock']);
                return redirect(url('admin/product'))->with('status', 'Đã cập nhật thành công bản ghi thành out_of_stock');
            } elseif ($act == 'delete') {
                Product::whereIn('id', $list_id)->delete();
                return redirect(url('admin/product'))->with('status', 'Đã xóa thành công thành bản ghi');
            } elseif ($act == 'restore') {
                Product::onlyTrashed()->restore();
                return redirect(url('admin/product'))->with('status', 'Đã khôi phục thành công thành bản ghi');
            } elseif ($act == 'forceDelete') {
                $product_thumb = Product::where('id', $list_id)->get();
                foreach ($product_thumb as $item) {
                    unlink($item->product_thumb);
                }
                Product::onlyTrashed()->forceDelete();
                return redirect(url('admin/product'))->with('status', 'Đã xóa vĩnh viễn thành công thành bản ghi');
            } else
                return redirect(url('admin/product'))->with('status', 'Chọn tác vụ để thực hiện');
        } else
            return redirect(url('admin/product'))->with('status', 'Chọn bản ghi để thực hiện');
    }

    // Ajax
    function ajax_product(Product $product)
    {
        return view('admin.product.ajax', compact('product'));
    }

    function ajax_select(Request $request)
    {
        $product_id = $request->product_id;
        $product_image = ProductImage::where('product_id', $product_id)->get();
        $count = $product_image->count();
        $output = '<form> ' . csrf_field() . '
        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên hình ảnh</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
        ';

        if ($count > 0) {
            $i = 0;
            foreach ($product_image as $key => $value) {
                $i++;
                $output .= '
                    <tr>
                        <th scope="row">' . $i . '</th>
                        <td>' . $value->image_name . '</td>
                        <td><img src="' . url($value->image_name) . '" width="120"></td>
                        <td>
                            <button type="button" class="btn btn-danger delete-ajax-product" data-id="' . $value->id . '"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                ';
            }
        } else {
            $output .= '
                    <tr>
                        <th colspan="4">Sẩn phẩm chưa có ảnh</th>
                    </tr>
                ';
        }

        $output .= '</tbody> </table> </form>';

        echo $output;
    }

    function ajax_upload(Request $request, Product $product)
    {
        $request->validate(
            [
                'list_image' => 'required|max:2048'
            ],
            [
                'required' => 'Vui lòng tải lên ảnh.',
                'max' => 'Kích thước ảnh không được vượt quá 2MB.',
            ]
        );

        if ($request->hasFile('list_image')) {
            $files = $request->file('list_image');
            foreach ($files as $file) {
                $file_name = time() . '-' . $file->getClientOriginalName();
                $path = 'public/uploads/product/';
                $file->move($path, $file_name);
                ProductImage::create(
                    [
                        'image_name' => $path . $file_name,
                        'product_id' => $product->id,
                        'user_id' => Auth::id()
                    ]
                );
            }
        }

        return redirect(route('product_ajax', $product->id))->with('status', 'Đã thêm thành công hình ảnh cho sản phẩm');
    }

    function ajax_delete(Request $request)
    {
        $product_image = $request->product_img_Id;
        $pro_img = ProductImage::find($product_image);
        unlink($pro_img->image_name);
        $pro_img->delete();
    }
}
