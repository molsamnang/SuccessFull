<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // Only Super Admin can access this controller
        $this->middleware('super_admin');
    }

    /**
     * Display a paginated list of users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 6);

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                // Add other searchable fields here if needed
            });
        }

        $users = $query->paginate($perPage)->withQueryString();

        return view('superadmin.users.index', compact('users'));
    }


    /**
     * Toggle user role between 'admin' and 'customer'.
     */
    public function toggleRole($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        if ($user->hasRole('customer')) {
            $user->syncRoles(['admin']);
            return redirect()->back()->with('success', 'User role changed to Admin.');
        }

        if ($user->hasRole('admin')) {
            $user->syncRoles(['customer']);
            return redirect()->back()->with('success', 'User role changed to Customer.');
        }

        $user->syncRoles(['customer']);
        return redirect()->back()->with('success', 'User role set to Customer.');
    }


    /**
     * Update user information and role.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'     => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email',
            'role'                  => 'required|string|exists:roles,name',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
