<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function index()
    {
        $orders = Order::paginate(5);

        // number
        $delivered = Order::where('status', 'delivered')->count();
        $processing = Order::where('status', 'processing')->count();
        $canceled = Order::where('status', 'canceled')->count();
        $doanhso = Order::where('status', 'delivered')->sum('total_amount');
        $number = [$delivered, $processing, $canceled, $doanhso];
        return view('admin.dashboard.index', compact('orders', 'number'));
    }
}
