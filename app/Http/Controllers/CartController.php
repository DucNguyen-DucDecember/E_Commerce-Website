<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index()
    {
        return view('pages.cart.index');
    }

    function add(Request $request, $product_id)
    {
        $quantity = $request->input('num-order');
        $product = Product::find($product_id);
        Cart::add(
            [
                'id' => $product->id,
                'name' => $product->product_name,
                'qty' => $quantity,
                'price' => $product->product_price,
                'options' =>
                    [
                        'product_thumb' => $product->product_thumb,
                        'product_slug' => $product->product_slug
                    ]
            ]
        );

        return redirect(url('gio-hang'));
    }

    function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect(url('gio-hang'));
    }

    function delete()
    {
        Cart::destroy();
        return redirect(url('gio-hang'));
    }

    function update(Request $request)
    {
        $qty = $request->qty;
        $rowId = $request->rowId;
        $id = $request->id;

        Cart::update($rowId, $qty);
        $product = Product::find($id);
        $sub_total = number_format($product->product_price * $qty, 0, '.', '.');
        $total = Cart::total();
        $number = Cart::count();
        $result =
            [
                'number' => $number,
                'total' => $total,
                'sub_total' => $sub_total
            ];

        echo json_encode($result);
    }

    function ajax_cart_add(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        Cart::add(
            [
                'id' => $product->id,
                'name' => $product->product_name,
                'qty' => 1,
                'price' => $product->product_price,
                'options' =>
                    [
                        'product_thumb' => $product->product_thumb,
                        'product_slug' => $product->product_slug
                    ]
            ]
        );

        $list_item = '';

        foreach (Cart::content() as $item) {
            $list_item .= '
            <li class="clearfix">
                <a href="' . url('/san-pham/' . $item->options->product_slug . '.html') . '"
                    title="" class="thumb fl-left">
                    <img src="' . url($item->options->product_thumb) . '" alt="">
                </a>
                <div class="info fl-right">
                    <a href="' . url('/san-pham/' . $item->options->product_slug . '.html') . '"
                        title="" class="product-name">' . $item->name . '</a>
                    <p class="price">' . number_format($item->price, 0, ' . ', ' . ') . 'đ</p>
                    <p class="qty">Số lượng: <span data-rowid="' . $item->rowId . '"
                            class="item-qty">' . $item->qty . '</span></p>
                </div>
            </li>
            ';
        }

        $number = Cart::count();
        $total = Cart::total();

        $result = [
            'list_item' => $list_item,
            'number' => $number,
            'total' => $total
        ];

        echo json_encode($result);
    }
}
