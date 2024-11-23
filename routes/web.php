<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Can;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // User
    Route::get('user', [UserController::class, 'index'])->can('user.view');
    Route::get('user/add', [UserController::class, 'add'])->can('user.add');
    Route::post('user/store', [UserController::class, 'store'])->can('user.add');
    Route::post('user/act', [UserController::class, 'act'])->can('user.view');
    Route::get('user/edit/{user}', [UserController::class, 'edit'])->name('user_edit');
    Route::post('user/update/{user}', [UserController::class, 'update'])->name('user_update')->can('user.edit');
    Route::get('user/delete/{user}', [UserController::class, 'delete'])->name('user_delete')->can('user.delete');

    // Post Category
    Route::get('post_category', [PostCategoryController::class, 'index'])->can('post.view');
    Route::post('post_category/add', [PostCategoryController::class, 'add'])->can('post.view');
    Route::get('post_category/edit/{post_category}', [PostCategoryController::class, 'edit'])->name('post_category_edit')->can('post.view');
    Route::post('post_category/update/{post_category}', [PostCategoryController::class, 'update'])->name('post_category_update')->can('post.view');
    Route::get('post_category/delete/{post_category}', [PostCategoryController::class, 'delete'])->name('post_category_delete')->can('post.view');

    // Post
    Route::get('post', [PostController::class, 'index'])->can('post.view');
    Route::get('post/add', [PostController::class, 'add'])->can('post.add');
    Route::post('post/store', [PostController::class, 'store'])->can('post.add');
    Route::post('post/act', [PostController::class, 'act'])->can(ability: 'post.add');
    Route::get('post/edit/{post}', [PostController::class, 'edit'])->name('post_edit')->can('post.edit');
    Route::post('post/update/{post}', [PostController::class, 'update'])->name('post_update')->can('post.edit');
    Route::get('post/delete/{post}', [PostController::class, 'delete'])->name('post_delete')->can('post.delete');

    // Product Category
    Route::get('product_category', [ProductCategoryController::class, 'index'])->can('product.view');
    Route::post('product_category/add', [ProductCategoryController::class, 'add'])->can('product.view');
    Route::get('product_category/edit/{product_category}', [ProductCategoryController::class, 'edit'])->name('product_category_edit')->can('product.view');
    Route::post('product_category/update/{product_category}', [ProductCategoryController::class, 'update'])->name('product_category_update')->can('product.view');
    Route::get('product_category/delete/{product_category}', [ProductCategoryController::class, 'delete'])->name('product_category_delete')->can('product.view');

    // Product
    Route::get('product', [ProductController::class, 'index'])->can('product.view');
    Route::get('product/add', [ProductController::class, 'add'])->can('product.add');
    Route::post('product/store', [ProductController::class, 'store'])->can('product.add');
    Route::post('product/act', [ProductController::class, 'act'])->can('product.add');
    Route::get('product/edit/{product}', [ProductController::class, 'edit'])->name('product_edit')->can('product.edit');
    Route::post('product/update/{product}', [ProductController::class, 'update'])->name('product_update')->can('product.edit');
    Route::get('product/delete/{product}', [ProductController::class, 'delete'])->name('product_delete')->can('product.delete');

    // Ajax add pictures to product
    Route::get('product/ajax/{product}', [ProductController::class, 'ajax_product'])->name('product_ajax')->can('product.add');
    Route::post('ajax/select', [ProductController::class, 'ajax_select'])->can('product.add');
    Route::post('ajax/upload/{product}', [ProductController::class, 'ajax_upload'])->name('ajax_upload')->can('product.add');
    Route::post('ajax/delete', [ProductController::class, 'ajax_delete'])->can('product.add');

    // Order
    Route::get('order', [OrderController::class, 'index'])->can('order.view');
    Route::post('order/act', [OrderController::class, 'order_act'])->can(ability: 'order.act');
    Route::post('order/update', [OrderController::class, 'update'])->can(ability: 'order.act');
    Route::get('order/detail/{order_id}', [OrderController::class, 'order_detail'])->name('order_detail')->can('order.detail');

    // Permission
    Route::get('permission/add', [PermissionController::class, 'add'])->can('permission.add');
    Route::post('permission/store', [PermissionController::class, 'store'])->can('permission.add');
    Route::get('permission/edit/{permission_id}', [PermissionController::class, 'edit'])->name('permission_edit')->can('permission.edit');
    Route::post('permission/update/{permission_id}', [PermissionController::class, 'update'])->name('permission_update')->can('permission.edit');
    Route::get('permission/delete/{permission_id}', [PermissionController::class, 'delete'])->name('permission_delete')->can('permission.delete');

    // Role
    Route::get('role', [RoleController::class, 'index'])->can('role.view');
    Route::get('role/add', [RoleController::class, 'add'])->can('role.add');
    Route::post('role/store', [RoleController::class, 'store'])->can('role.add');
    Route::get('role/edit/{role_id}', [RoleController::class, 'edit'])->name('role_edit')->can('role.edit');
    Route::post('role/update/{role_id}', [RoleController::class, 'update'])->name('role_update')->can('role.edit');
    Route::get('role/delete/{role_id}', [RoleController::class, 'delete'])->name('role_delete')->can('role.delete');
});

// Client
Route::get('/', [TrangChuController::class, 'index']);
Route::get('/danh-muc/{category_slug}', [TrangChuController::class, 'product_category'])->name('product_category');
Route::get('/san-pham/{product_slug}.html', [TrangChuController::class, 'detail'])->name('product_detail');
Route::match(['get', 'post'], 'tim-kiem', [TrangChuController::class, 'tim_kiem'])->name('tim_kiem');

// Ajax filt price
Route::post('ajax/filt_price', [TrangChuController::class, 'ajax_filt'])->name('ajax_filt_price');

// Cart
Route::get('gio-hang', [CartController::class, 'index']);
Route::post('gio-hang/add/{product_id}', [CartController::class, 'add'])->name('gio_hang_add');
Route::get('gio-hang/remove/{rowId}', [CartController::class, 'remove'])->name('gio_hang_remove');
Route::get('gio-hang/delete', [CartController::class, 'delete']);
Route::post('gio-hang/update', [CartController::class, 'update']);
Route::post('ajax/add_cart', [CartController::class, 'ajax_cart_add']);

// Checkout
Route::get('thanh-toan', [CheckoutController::class, 'index']);
Route::post('thanh-toan/store', [CheckoutController::class, 'store']);
Route::get('dat-hang-thanh-cong', [CheckoutController::class, 'order_success']);
Route::get('mua-ngay/{product_slug}.html', [CheckoutController::class, 'mua_ngay'])->name('mua_ngay');
Route::post('mua-ngay/store/{product_slug}', [CheckoutController::class, 'mua_ngay_store'])->name('mua_ngay_store');

// Mail
Route::get('mail/order_confirm', [CheckoutController::class, 'order_confirm']);
Route::get('mail/buynow_confirm', [CheckoutController::class, 'buynow_confirm']);

// Post
Route::get('bai-viet', [PostController::class, 'bai_viet']);
Route::get('bai-viet/{post_slug}.html', [PostController::class, 'bai_viet_detail'])->name('bai_viet_detail');