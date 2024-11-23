<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permission']);
            return $next($request);
        });
    }

    function index()
    {
        $roles = Role::paginate(5);
        return view('admin.role.index', compact('roles'));
    }

    function add()
    {
        $permissions = Permission::all()->groupBy(function ($permissions) {
            return explode('.', $permissions->slug)[0];
        });
        return view('admin.role.add', compact('permissions'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'description' => 'required',
                'name' => 'required|unique:roles,name',
                'permission_id' => 'nullable|array',
                'permission_id.*' => 'exists:permissions,id'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên vai trò đã tồn tại'
            ],
            [
                'name' => 'Tên vai trò',
                'description' => 'Mô tả',
                'permission_id' => 'Quyền của user',
            ]
        );

        $role = Role::create(
            [
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]
        );

        $role->permission()->attach($request->input('permission_id'));

        return redirect(url('admin/role'))->with('add_status', 'Đã thêm thành công vai trò');
    }

    function edit(Role $role_id)
    {
        $permissions = Permission::all()->groupBy(function ($permissions) {
            return explode('.', $permissions->slug)[0];
        });
        return view('admin.role.edit', compact('permissions', 'role_id'));
    }

    function update(Request $request, Role $role_id)
    {
        $request->validate(
            [
                'name' => 'required|unique:roles,name,' . $role_id->id,
                'permission_id' => 'nullable|array',
                'permission_id.*' => 'exists:permissions,id'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Tên vai trò đã tồn tại'
            ],
            [
                'name' => 'Tên vai trò',
                'description' => 'Mô tả',
                'permission_id' => 'Quyền của user',
            ]
        );

        $role_id->update(
            [
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]
        );

        $role_id->permission()->sync($request->input('permission_id', []));
        return redirect(url('admin/role'))->with('edit_status', 'Đã cập nhật thành công vai trò');
    }

    function delete(Role $role_id)
    {
        $role_id->delete();
        $role_id->permission()->detach();
        return redirect(url('admin/role'))->with('delete_status', 'Đã xóa thành công vai trò');
    }
}
