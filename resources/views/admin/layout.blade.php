<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{url('public/admin/css/style.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/c5e4ro5gwxie7ebmpg5ozj19mwo9ycauzckerdk4yqz15a8d/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <title>Admintrator</title>
</head>

<body>
    <?php $module_active = session('module_active') ?>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">PROJECT BADMINTON SHOP</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{url('admin/post/add')}}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{url('admin/product/add')}}">Thêm sản phẩm</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Tài khoản</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Thoát
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{$module_active == 'dashboard' ? 'active' : ''}}">
                        <a href="{{url('admin/dashboard')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                    </li>
                    @canany(['post.view', 'post.add', 'post.edit', 'post.delete'])
                        <li class="nav-link {{$module_active == 'post' ? 'active' : ''}}">
                            <a href="{{url('admin/post')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Bài viết
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li><a href="{{url('admin/post/add')}}">Thêm mới</a></li>
                                <li><a href="{{url('admin/post')}}">Danh sách</a></li>
                                <li><a href="{{url('admin/post_category')}}">Danh mục</a></li>
                            </ul>
                        </li>
                    @endcanany
                    @canany(['product.view', 'product.add', 'product.edit', 'product.delete'])
                        <li class="nav-link {{$module_active == 'product' ? 'active' : ''}}">
                            <a href="{{url('admin/product')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Sản phẩm
                            </a>
                            <i class="arrow fas fa-angle-down"></i>
                            <ul class="sub-menu">
                                <li><a href="{{url('admin/product/add')}}">Thêm mới</a></li>
                                <li><a href="{{url('admin/product')}}">Danh sách</a></li>
                                <li><a href="{{url('admin/product_category')}}">Danh mục</a></li>
                            </ul>
                        </li>
                    @endcanany
                    @canany(['order.view', 'order.act', 'order.detail'])
                        <li class="nav-link {{$module_active == 'order' ? 'active' : ''}}">
                            <a href="{{url('admin/order')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Bán hàng
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li><a href="{{url('admin/order')}}">Đơn hàng</a></li>
                            </ul>
                        </li>
                    @endcanany
                    @canany(['user.view', 'user.add', 'user.edit', 'user.delete'])
                        <li class="nav-link {{$module_active == 'user' ? 'active' : ''}}">
                            <a href="{{url('admin/user')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Users
                            </a>
                            <i class="arrow fas fa-angle-right"></i>

                            <ul class="sub-menu">
                                <li><a href="{{url('admin/user/add')}}">Thêm mới</a></li>
                                <li><a href="{{url('admin/user')}}">Danh sách</a></li>
                            </ul>
                        </li>
                    @endcanany
                    @canany(['permission.view', 'permission.add', 'permission.edit', 'permission.update', 'role.view', 'role.add', 'role.eidt', 'role.delete'])
                        <li class="nav-link {{$module_active == 'permission' ? 'active' : ''}}">
                            <a href="{{url('admin/role')}}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Phân quyền
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                @can('permission.add')
                                    <li><a href="{{url('admin/permission/add')}}">Quyền</a></li>
                                @endcan
                                @can('role.add')
                                    <li><a href="{{url('admin/role/add')}}">Thêm vai trò</a></li>
                                @endcan
                                @can('role.view')
                                    <li><a href="{{url('admin/role')}}">Danh sách vai trò</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                </ul>
            </div>
            <div id="wp-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{url('public/admin/js/app.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

</body>

</html>