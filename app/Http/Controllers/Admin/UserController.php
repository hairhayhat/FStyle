<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
class UserController extends Controller
{
              public function index()
              {
                            $users = User::paginate(10);
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