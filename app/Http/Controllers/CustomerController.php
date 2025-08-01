<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('full_name', 'like', "%{$search}%")
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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->back()->with('success', 'Customer created successfully!');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }

    public function show(Customer $customer)
    {
        return view('Customer.show', compact('customer'));
    }
}
