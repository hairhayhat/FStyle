<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 10);
        $status = $request->get('email_verified');

        $query = User::query();

        if (!empty($status)) {
            if ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        if ($request->ajax()) {
            $html = view('admin.partials.table-users', compact('users'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.user.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật vai trò thành công');
    }
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

}