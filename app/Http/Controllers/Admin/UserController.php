<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
class UserController extends Controller

{
public function index()
{
              $users = User::all();
              return view('admin.user.index', compact('users'));
}
public function edit(User $user)
{
              return view('admin.user.edit', compact('user'));
}
}