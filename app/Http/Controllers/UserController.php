<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    function index(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
            'restore' => 'Khôi phục',
            'forceDelete' => 'Xóa vĩnh viễn'
        ];
        if ($request->status) {
            $status = $request->status;
            if ($status == 'active') {
                unset($list_act['restore'], $list_act['forceDelete']);
                $users = User::paginate(5);
            } elseif ($status == 'delete') {
                unset($list_act['delete']);
                $users = User::onlyTrashed()->paginate(5);
            }
        } else {
            $search = '';
            if ($request->search) {
                $search = $request->search;
                $users = User::where('name', 'LIKE', "%{$search}%")->paginate(5);
            }
            unset($list_act['restore'], $list_act['forceDelete']);
            $users = User::with('role')->where('name', 'LIKE', "%{$search}%")->paginate(5);
        }
        $active = User::count();
        $deleted = User::onlyTrashed()->count();
        $number = [$active, $deleted];

        return view('admin.user.index', compact('users', 'number', 'list_act'));

    }
    function add()
    {
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => 'Email đã tồn tại'
            ],
            [
                'name' => 'Họ và tên',
                'email' => 'Email',
                'password' => 'Mật khẩu'
            ]
        );

        $user = User::create(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]
        );
        $user->role()->sync($request->input('roles'));

        if ($user)
            return redirect(url('admin/user'))->with('add_status', 'Đã thêm thành công user');
    }

    function act(Request $request)
    {
        $list_id = $request->list_id;

        if ($list_id) {
            foreach ($list_id as $key => $id) {
                if ($id == Auth::id())
                    unset($list_id[$key]);
            }

            $act = $request->act;
            if ($act == 'delete') {
                User::whereIn('id', $list_id)->delete();
                return redirect(url('admin/user'))->with('status', 'Đã xóa tạm thời bản ghi');
            } elseif ($act == 'restore') {
                User::onlyTrashed()->whereIn('id', $list_id)->restore();
                return redirect(url('admin/user'))->with('status', 'Đã khôi phục bản ghi');
            } elseif ($act == 'forceDelete') {
                User::onlyTrashed()->whereIn('id', $list_id)->forceDelete();
                return redirect(url('admin/user'))->with('status', 'Đã xóa vĩnh viễn bản ghi');
            } else
                return redirect(url('admin/user'))->with('status', 'Chọn tác vụ để thực hiện');
        } else
            return redirect(url('admin/user'))->with('status', 'Chọn bản ghi để thực hiện');
    }

    function edit(User $user)
    {
        $roles = Role::all();
        $selected_roles = $user->role->pluck('id')->toArray();
        return view('admin.user.edit', compact('user', 'selected_roles', 'roles'));
    }

    function update(Request $request, User $user)
    {
        $request->validate(
            [
                'name' => 'required',
                // 'password' => 'required'
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Họ và tên',
                'password' => 'Mật khẩu'
            ]
        );

        $user->update(
            [
                'name' => $request->input('name'),
                // 'password' => Hash::make($request->input('password'))
            ]
        );
        $user->role()->sync($request->input('roles'));
        return redirect(url('admin/user'))->with('edit_status', 'Đã cập nhật thành công user');
    }

    function delete(User $user)
    {
        $user->delete();
        return redirect(url('admin/user'))->with('delete_status', 'Đã xóa thành công user');
    }
}