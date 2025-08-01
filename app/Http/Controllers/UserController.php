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
        // Only Super Admin middleware — adjust as per your middleware
        $this->middleware('super_admin');
    }

    /**
     * Display paginated users.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    /**
     * Toggle user role between User and Admin.
     */
    public function toggleRole($id)
    {
        $user = User::findOrFail($id);

        // Prevent changing own role
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        if ($user->hasRole('User')) {
            $user->syncRoles(['Admin']);
            return redirect()->back()->with('success', 'User role changed to Admin.');
        }

        if ($user->hasRole('Admin')) {
            $user->syncRoles(['User']);
            return redirect()->back()->with('success', 'User role changed to User.');
        }

        // If other roles or none, assign User role
        $user->syncRoles(['User']);
        return redirect()->back()->with('success', 'User role set to User.');
    }

    /**
     * Update user info and role.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:6|confirmed', // remember to add password_confirmation field
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
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
