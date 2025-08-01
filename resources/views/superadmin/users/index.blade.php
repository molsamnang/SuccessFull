@extends('layouts.app')

@section('data_one')
<div class="container-fluid bg-dark text-white p-4 min-vh-100">
    <h2>User Role Management</h2>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <div class="table-responsive mt-4">
        <table class="table table-dark table-bordered table-hover text-white align-middle text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th> {{-- Masked, no real value --}}
                    <th>Role</th>
                    <th>ActionRole</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $users->firstItem() + $key }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>********</td> {{-- Never show real password --}}
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $user->getRoleNames()->first() ?? 'None' }}
                        </span>
                    </td>

                    {{-- Toggle Role --}}
                    <td>
                        @if (Auth::id() != $user->id)
                        <form action="{{ route('superadmin.users.toggle-role', $user->id) }}" method="POST" onsubmit="return confirm('Toggle role for this user?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">
                                {{ $user->hasRole('User') ? 'Make Admin' : 'Make User' }}
                            </button>
                        </form>
                        @else
                        <span class="text-muted">You</span>
                        @endif
                    </td>

                    {{-- Edit & Delete Buttons --}}
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                            Delete
                        </button>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Name --}}
                                    <div class="mb-3">
                                        <label for="name{{ $user->id }}" class="form-label">Name</label>
                                        <input type="text" name="name" id="name{{ $user->id }}" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label for="email{{ $user->id }}" class="form-label">Email</label>
                                        <input type="email" name="email" id="email{{ $user->id }}" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    {{-- Role Dropdown --}}
                                    <div class="mb-3">
                                        <label for="role{{ $user->id }}" class="form-label">Role</label>
                                        <select name="role" id="role{{ $user->id }}" class="form-select" required>
                                            @foreach(['super_admin', 'admin', 'writer', 'customer', 'User', 'Admin'] as $role)
                                            <option value="{{ $role }}" @if($user->hasRole($role)) selected @endif>{{ ucfirst($role) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Password (optional) --}}
                                    <div class="mb-3">
                                        <label for="password{{ $user->id }}" class="form-label">New Password (leave blank to keep current)</label>
                                        <input type="password" name="password" id="password{{ $user->id }}" class="form-control" autocomplete="new-password" placeholder="Enter new password">
                                    </div>

                                    {{-- Password Confirmation --}}
                                    <div class="mb-3">
                                        <label for="password_confirmation{{ $user->id }}" class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation{{ $user->id }}" class="form-control" autocomplete="new-password" placeholder="Confirm new password">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Delete Modal --}}
                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Confirm Delete</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete user <strong>{{ $user->name }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
