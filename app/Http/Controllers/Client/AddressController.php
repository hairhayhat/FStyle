<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create()
    {
        $addresses = auth()->user()->addresses;
        return view('client.dashboard.address', compact(['addresses']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $address = new Address();

        $user_id = auth()->user()->id;

        $address->user_id = $user_id;
        $address->full_name = $request->full_name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->nickname = $request->nickname;
        $address->is_default = $request->has('is_default') ? 1 : 0;

        if ($request->has('is_default')) {
            Address::where('user_id', auth()->user()->id)->update(['is_default' => 0]);
        }

        $address->save();

        return back()->with('success', 'Tạo địa chỉ mới thành công');

    }
}
