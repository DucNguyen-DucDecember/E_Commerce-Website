<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permission']);
            return $next($request);
        });
    }
    function add()
    {
        $list_permission = Permission::all()->groupBy(function ($list_permission) {
            return explode('.', $list_permission->slug)[0];
        });

        return view('admin.permission.add', compact('list_permission'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'slug' => 'required'
            ],
            [
                'required' => ':attribute không được để trống'
            ],
            [
                'name' => 'Tên quyền',
                'slug' => 'Slug'
            ]
        );

        Permission::create(
            [
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description')
            ]
        );

        return redirect(url('admin/permission/add'))->with('add_status', 'Đã thêm thành công quyền');
    }

    function edit(Permission $permission_id)
    {
        return view('admin.permission.edit', compact('permission_id'));
    }

    function update(Request $request, Permission $permission_id)
    {
        $request->validate(
            [
                'name' => 'required',
                'slug' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại'
            ],
            [
                'name' => 'Tên quyền',
                'slug' => 'Slug'
            ]
        );

        $permission_id->update(
            [
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description')
            ]
        );

        return redirect(url('admin/permission/add'))->with('edit_status', 'Đã cập nhật thành công quyền');
    }

    function delete(Permission $permission_id)
    {
        $permission_id->delete();
        return redirect(url('admin/permission/add'))->with('delete_status', 'Đã xóa thành công quyền');
    }
}
