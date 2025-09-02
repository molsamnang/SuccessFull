<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;

class EditProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function profile(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'gender' => $customer->gender,
                'profile_image_url' => $customer->profile_image
                    ? asset('storage/' . $customer->profile_image)
                    : asset('assets/images/default-avatar.png'),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $customer = $request->user(); 

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|max:2048'
        ]);

        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle image
        if ($request->hasFile('profile_image')) {
            if ($customer->profile_image && Storage::disk('public')->exists($customer->profile_image)) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $customer->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'gender' => $customer->gender,
                'profile_image_url' => $customer->profile_image
                    ? asset('storage/' . $customer->profile_image)
                    : asset('assets/images/default-avatar.png'),
            ]
        ]);
    }
}
