<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class TrangChuController extends Controller
{
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

    function render_menu($data, $menu_id = "main-menu", $menu_class = '', $parent_id = 0, $level = 0)
    {
        if ($level == 0) {
            $result = "<ul id='{$menu_id}' class='{$menu_class}'>";
        } else {
            $result = "<ul class='sub-menu'>";
        }

        foreach ($data as $v) {
            if ($v->parent_id == $parent_id) {
                $result .= "<li>";
                $result .= "<a href='" . route('product_category', ['category_slug' => $v->category_slug]) . "'>{$v['category_name']}</a>";


                if ($this->has_child($data, $v['id']))//Kt mang co con la phan tu cua dang xet
                {
                    $result .= $this->render_menu($data, $menu_id, $menu_class, $v['id'], $level + 1);//Tim phan tu co cung id 
                }
                $result .= "</li>";
            }
        }
        $result .= "</ul>";
        return $result;
    }
    public function index()
    {
        $pro_cates = ProductCategory::all();
        $pro_cates = $this->render_menu($pro_cates, '', 'list-item');
        $best_sellers = Product::orderBy('stock_quantity', 'desc')->limit(10)->get();
        $featured = Product::where('is_featured', 1)->limit(10)->get();
        $categories = ProductCategory::with('product')->get();
        return view('pages.home.index', compact('pro_cates', 'best_sellers', 'featured', 'categories'));
    }

    function detail($product_slug)
    {
        $product = Product::where('product_slug', $product_slug)->with('product_image')->with('product_category')->first();
        $related_pro = Product::where('category_id', $product->category_id)->whereNotIn('id', [$product->id])->limit(7)->get();
        $pro_cates = ProductCategory::all();
        $pro_cates = $this->render_menu($pro_cates, '', 'list-item');
        return view('pages.product.detail', compact('product', 'pro_cates', 'related_pro'));
    }

    function product_category(Request $request, $category_slug)
    {
        $pro_cates = ProductCategory::all();
        $pro_cates = $this->render_menu($pro_cates, '', 'list-item');

        $category = ProductCategory::where('category_slug', $category_slug)->first();

        // Order products
        if ($request->order_product) {
            $order_product = $request->order_product;
            if ($order_product == 1) {
                $products = Product::where('category_id', $category->id)->orderBy('product_name', 'asc')->paginate(5);
            } elseif ($order_product == 2) {
                $products = Product::where('category_id', $category->id)->orderBy('product_name', 'desc')->paginate(5);
            } elseif ($order_product == 3) {
                $products = Product::where('category_id', $category->id)->orderBy('product_price', 'desc')->paginate(5);
            } elseif ($order_product == 4) {
                $products = Product::where('category_id', $category->id)->orderBy('product_price', 'asc')->paginate(5);
            }
        } else
            $products = Product::where('category_id', $category->id)->paginate(5);

        return view('pages.product.cate_pro', compact('category', 'products', 'pro_cates'));
    }

    function ajax_filt(Request $request)
    {
        $range_price = $request->filt_price;
        $category_id = $request->category_id;

        if ($range_price == 0) {
            $products = Product::where('category_id', $category_id)->whereBetween('product_price', ['0', '500000'])->get();
        } elseif ($range_price == 1) {
            $products = Product::where('category_id', $category_id)->whereBetween('product_price', ['500000', '1000000'])->get();
        } else
            $products = Product::where('category_id', $category_id)->whereBetween('product_price', ['1000000', '5000000'])->get();

        $result = '';
        $result_paginate = '';

        if (!$products->isEmpty()) {
            foreach ($products as $item) {
                $result .= '
                <li>
                    <a href="' . route('product_detail', $item->product_slug) . '" title="" class="thumb">
                        <img src="' . url($item->product_thumb) . '">
                    </a>
                    <a href="' . route('product_detail', $item->product_slug) . '" title=""
                        class="product-name">' . $item->product_name . '</a>
                    <div class="price">
                        <span class="new">' . number_format($item->product_price, 0, ' . ', ' . ') . 'đ</span>
                    </div>
                    <div class="action clearfix">
                        <a href="" type="button" title="Thêm giỏ hàng" class="add-cart fl-left"
                                        data-id="' . $item->id . '">Thêm
                                        giỏ hàng</a>
                        <a href="' . route('mua_ngay', $item->product_slug) . '" title="Mua ngay"
                            class="buy-now fl-right">Mua ngay</a>
                    </div>
                </li>
                ';
            }

        } else
            $result .= 'Không có sản phẩm';

        $array = ['result' => $result, 'result_paginate' => $result_paginate];
        return json_encode($array);
    }

    function tim_kiem(Request $request)
    {
        $pro_cates = ProductCategory::all();
        $pro_cates = $this->render_menu($pro_cates, '', 'list-item');
        $search = $request->input('s');

        // Order products
        if ($request->order_product) {
            $order_product = $request->order_product;
            if ($order_product == 1) {
                $products = Product::where('product_name', 'LIKE', "%{$search}%")->orderBy('product_name', 'asc')->paginate(5);
            } elseif ($order_product == 2) {
                $products = Product::where('product_name', 'LIKE', "%{$search}%")->orderBy('product_name', 'desc')->paginate(5);
            } elseif ($order_product == 3) {
                $products = Product::where('product_name', 'LIKE', "%{$search}%")->orderBy('product_price', 'desc')->paginate(5);
            } elseif ($order_product == 4) {
                $products = Product::where('product_name', 'LIKE', "%{$search}%")->orderBy('product_price', 'asc')->paginate(5);
            }
        } else
            $products = Product::where('product_name', 'LIKE', "%{$search}%")->paginate(5);

        return view('pages.home.tim_kiem', compact('products', 'pro_cates'));
    }
}
