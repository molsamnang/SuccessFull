<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page',20);
        $search = $request->get('search', '');

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $role = strtolower(auth()->user()->role ?? '');
        $role = str_replace('super_admin', 'superadmin', $role);

        return view('Customer.index', compact('customers', 'perPage', 'search', 'role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:customers,email',
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'nullable|in:male,female,other',
            'address'       => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        Customer::create($validated);

        return redirect()->back()->with('success', 'Customer created successfully!');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'gender', 'phone', 'address']);

        if ($request->hasFile('profile_image')) {
            if ($customer->profile_image) {
                Storage::delete('public/' . $customer->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        $customer->update($data);

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }


    public function destroy(Customer $customer)
    {
        // Delete profile image if exists
        if ($customer->profile_image && Storage::disk('public')->exists($customer->profile_image)) {
            Storage::disk('public')->delete($customer->profile_image);
        }

        // Delete the customer record
        $customer->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }

    public function show(Customer $customer)
    {
        return view('Customer.show', compact('customer'));
    }
}
