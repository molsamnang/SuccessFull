<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    /**
     * Register a new customer
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:customers,email',
            'password'      => 'required|string|min:6|confirmed',
            'phone'         => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:4048',
            'gender'        => 'nullable|string|in:male,female,other',
        ]);

        // Handle profile image
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Create customer
        $customer = Customer::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'profile_image' => $imagePath,
            'gender'        => $request->gender,
        ]);

        // Generate token
        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'customer'     => $this->transformCustomer($customer),
        ], 201);
    }

    /**
     * Login customer
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found',
            ], 404);
        }

        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password is incorrect',
            ], 401);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'customer'     => $this->transformCustomer($customer),
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    private function transformCustomer(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'gender' => $customer->gender,
            'profile_image_url' => $customer->profile_image
                ? asset('storage/' . $customer->profile_image)
                : asset('assets/images/default-avatar.png') // fallback image
        ];
    }
    /**
     * Update authenticated customer profile
     */
    public function profile(Request $request)
    {
        $customer = $request->user();
        return response()->json(['customer' => $this->transformCustomer($customer)]);
    }

    /**
     * Update authenticated customer profile
     */
    public function update(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($customer->profile_image && Storage::disk('public')->exists($customer->profile_image)) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $customer->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $this->transformCustomer($customer),
        ]);
    }
}
