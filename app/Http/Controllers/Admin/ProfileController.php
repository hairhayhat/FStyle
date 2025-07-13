<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function editProfile()
    {
        return view('admin.profile.profile');
    }
}
