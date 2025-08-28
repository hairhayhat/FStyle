<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create()
    {
        $addresses = Auth::user()->addresses;
        return view('client.dashboard.address', compact(['addresses']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $address = new Address();
        $user_id = Auth::user()->id;

        $address->user_id = $user_id;
        $address->full_name = $request->full_name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->nickname = $request->nickname;
        $address->is_default = $request->has('is_default') ? 1 : 0;

        if ($request->has('is_default')) {
            Address::where('user_id', $user_id)->update(['is_default' => 0]);
        }

        $address->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $address->id,
                    'nickname' => $address->nickname,
                    'full_name' => $address->full_name,
                    'phone' => $address->phone,
                    'address' => $address->address,
                    'is_default' => $address->is_default
                ]
            ]);
        }
        return redirect()->back()->with('success', 'Đã thêm địa chỉ');
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);
        if ($address->orders()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Địa chỉ này đang được sử dụng, không thể chỉnh sửa.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $address = Address::findOrFail($id);

        $address->full_name = $request->full_name;
        $address->nickname = $request->nickname;
        $address->phone = $request->phone;
        $address->address = $request->address;

        if ($request->has('is_default')) {
            if (!$address->is_default) {
                Address::where('user_id', Auth::id())->update(['is_default' => 0]);
                $address->is_default = 1;
            }
        }
        $address->save();

        return back()->with('success', 'Cập nhật địa chỉ thành công');
    }

    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $user_id = Auth::id();

        $isUsed = $address->orders()->exists();
        if ($isUsed) {
            return back()->with('error', 'Địa chỉ này đang được sử dụng, không thể xóa.');
        }

        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $latestAddress = Address::where('user_id', $user_id)->latest()->first();
            if ($latestAddress) {
                $latestAddress->is_default = 1;
                $latestAddress->save();
            }
        }

        return back()->with('success', 'Đã xóa địa chỉ thành công.');
    }

}
