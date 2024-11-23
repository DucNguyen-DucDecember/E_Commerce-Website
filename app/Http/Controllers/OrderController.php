<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }
    function index(Request $request)
    {
        $list_act = [
            'pending' => 'Đang chờ',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã ship',
            'delivered' => 'Đã vận chuyển',
            'canceled' => 'Hủy',
            'delete' => 'Vô hiệu hóa',
            'restore' => 'Khôi phục',
            'forceDelete' => 'Xóa vĩnh viễn'
        ];

        if ($request->status) {
            $status = $request->status;
            if ($status == 'pending') {
                $orders = Order::where('status', 'pending')->paginate(5);
                unset($list_act['pending'], $list_act['restore'], $list_act['forceDelete']);
            } elseif ($status == 'processing') {
                $orders = Order::where('status', 'processing')->paginate(5);
                unset($list_act['processing'], $list_act['restore'], $list_act['forceDelete']);
            } elseif ($status == 'shipped') {
                $orders = Order::where('status', 'shipped')->paginate(5);
                unset($list_act['shipped'], $list_act['restore'], $list_act['forceDelete']);
            } elseif ($status == 'delivered') {
                $orders = Order::where('status', 'delivered')->paginate(5);
                unset($list_act['delivered'], $list_act['restore'], $list_act['forceDelete']);
            } elseif ($status == 'canceled') {
                $orders = Order::where('status', 'canceled')->paginate(5);
                unset($list_act['canceled'], $list_act['restore'], $list_act['forceDelete']);
            } elseif ($status == 'delete') {
                $orders = Order::onlyTrashed()->paginate(5);
                unset($list_act['delete'], $list_act['pending'], $list_act['processing'], $list_act['shipped'], $list_act['delivered'], $list_act['canceled']);
            }
        } else {
            $orders = Order::where('status', 'delivered')->paginate(5);
            unset($list_act['delivered']);
        }

        //number
        $pending = Order::where('status', 'pending')->count();
        $processing = Order::where('status', 'processing')->count();
        $shipped = Order::where('status', 'shipped')->count();
        $delivered = Order::where('status', 'delivered')->count();
        $canceled = Order::where('status', 'canceled')->count();
        $delete = Order::onlyTrashed()->count();
        $number = [$pending, $processing, $shipped, $delivered, $canceled, $delete];

        return view('admin.order.index', compact('orders', 'number', 'list_act'));
    }

    function order_act(Request $request)
    {
        $list_act = $request->list_act;
        $list_order = $request->list_order;

        if ($list_order) {
            if ($list_act == 'pending') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->update(['status' => 'pending']);
                return redirect('admin/order')->with('status', 'Đã cập nhật thành công trạng thái thành đang chờ');
            } elseif ($list_act == 'processing') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->update(['status' => 'processing']);
                return redirect('admin/order')->with('status', 'Đã cập nhật thành công trạng thái thành đang chờ');
            } elseif ($list_act == 'shipped') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->update(['status' => 'shipped']);
                return redirect('admin/order')->with('status', 'Đã cập nhật thành công trạng thái thành đã vận chuyển');
            } elseif ($list_act == 'delivered') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->update(['status' => 'delivered']);
                return redirect('admin/order')->with('status', 'Đã cập nhật thành công trạng thái thành đã vận chuyển');
            } elseif ($list_act == 'canceled') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->update(['status' => 'canceled']);
                return redirect('admin/order')->with('status', 'Đã cập nhật thành công trạng thái thành hủy');
            } elseif ($list_act == 'delete') {
                $list_order = $request->list_order;
                Order::whereIn('id', $list_order)->delete();
                return redirect('admin/order')->with('status', 'Đã vô hiệu hóa bản ghi');
            } elseif ($list_act == 'restore') {
                $list_order = $request->list_order;
                Order::onlyTrashed()->whereIn('id', $list_order)->restore();
                return redirect('admin/order')->with('status', 'Đã khôi phục thành bản ghi');
            } elseif ($list_act == 'forceDelete') {
                $list_order = $request->list_order;
                Order::onlyTrashed()->whereIn('id', $list_order)->forceDelete();
                return redirect('admin/order')->with('status', 'Đã xóa vĩnh viễn bản ghi');
            } else
                return redirect('admin/order')->with('status', 'Chọn bản ghi để thự hiện');
        } else
            return redirect('admin/order')->with('status', 'Chọn tác vụ để thực hiện');
    }

    function order_detail(Order $order_id)
    {
        $customer = Order::with('customer')->where('id', $order_id->id)->first();
        $order_items = OrderItem::with('product')->where('order_id', $order_id->id)->get();

        return view('admin.order.detail', compact('order_id', 'customer', 'order_items'));
    }

    function update(Request $request)
    {
        $order_id = $request->order_id;
        Order::where('id', $order_id)->update(['status' => $request->status]);

        return redirect(route('order_detail', $order_id))->with('status', 'Đã cập nhật thanh công trạng thái của đơn hàng');
    }
}
