<?php

namespace App\Http\Controllers;

use App\Mail\Buynow;
use App\Mail\OrderConfirm;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    function index()
    {
        return view('pages.checkout.index');
    }

    function store(Request $request)
    {
        $cart_total = Cart::total();
        $cart_number = (int) str_replace('.', '', $cart_total);

        $request->validate(
            [
                'fullname' => 'required',
                'email' => 'required',
                'address' => 'required',
                'phone_number' => 'required|numeric',
                'payment_method' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => 'Số điện thoại phải là ký tụ số'
            ],
            [
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'address' => 'Địa chỉ',
                'phone_number' => 'Số điện thoại',
                'payment_method' => 'Phương thức thanh toán'
            ]
        );

        $customer_id = Customer::insertGetId(
            [
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address')
            ]
        );

        $order_id = Order::insertGetId(
            [
                'order_code' => 'ORDER-' . time() . '-' . Str::upper(Str::random(4)),
                'total_quantity' => Cart::count(),
                'total_amount' => $cart_number,
                'order_date' => Carbon::now(),
                'payment_method' => $request->input('payment_method'),
                'shipping_address' => $request->input('address'),
                'customer_id' => $customer_id,
                'note' => $request->input('note'),
                'created_at' => Carbon::now()
            ]
        );

        foreach (Cart::content() as $item) {
            OrderItem::create(
                [
                    'order_id' => $order_id,
                    'product_id' => $item->id,
                    'product_quantity' => $item->qty,
                    'product_price' => $item->price
                ]
            );
        }

        Mail::to($request->input('email'))->send(new OrderConfirm($request->input('fullname'), Carbon::now(), $request->input('address'), $request->input('phone_number'), $request->input('payment_method'), Cart::content(), Cart::total()));

        return redirect(url('mail/order_confirm'));
    }

    function order_success()
    {
        return view('pages.checkout.success');
    }

    function order_confirm()
    {
        Cart::destroy();
        return redirect(url('dat-hang-thanh-cong'));
    }

    function mua_ngay($product_slug)
    {
        $product = Product::where('product_slug', $product_slug)->first();
        return view('pages.checkout.mua_ngay', compact('product'));
    }

    function mua_ngay_store(Request $request, $product_slug)
    {
        $product = Product::where('product_slug', $product_slug)->first();

        $request->validate(
            [
                'fullname' => 'required',
                'email' => 'required',
                'address' => 'required',
                'phone_number' => 'required|numeric',
                'payment_method' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => 'Số điện thoại phải là ký tụ số'
            ],
            [
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'address' => 'Địa chỉ',
                'phone_number' => 'Số điện thoại',
                'payment_method' => 'Phương thức thanh toán'
            ]
        );

        $customer_id = Customer::insertGetId(
            [
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address')
            ]
        );

        $order_id = Order::insertGetId(
            [
                'order_code' => 'ORDER-' . time() . '-' . Str::upper(Str::random(4)),
                'total_quantity' => 1,
                'total_amount' => $product->product_price,
                'order_date' => Carbon::now(),
                'payment_method' => $request->input('payment_method'),
                'shipping_address' => $request->input('address'),
                'customer_id' => $customer_id,
                'note' => $request->input('note'),
                'created_at' => Carbon::now()
            ]
        );

        OrderItem::create(
            [
                'order_id' => $order_id,
                'product_id' => $product->id,
                'product_quantity' => 1,
                'product_price' => $product->product_price
            ]
        );

        Mail::to($request->input('email'))->send(new Buynow($request->input('fullname'), Carbon::now(), $request->input('address'), $request->input('phone_number'), $request->input('payment_method'), $product->product_name, $product->product_price, $product->product_thumb));

        return redirect(url('mail/buynow_confirm'));
    }

    function buynow_confirm()
    {
        return redirect(url('dat-hang-thanh-cong'));
    }
}
