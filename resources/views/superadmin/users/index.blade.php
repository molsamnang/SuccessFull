@extends('layouts.app')

@section('data_one')
    @php
        $role = strtolower(auth()->user()->role ?? '');
        $role = str_replace('super_admin', 'superadmin', $role);
    @endphp

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
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

    <div class="container">
        <div class="row" style="max-width:100%;">
            <div class="col-12">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">User Role Management</h4>
                    </div>

                    <!-- Search + PerPage Form -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0" style="width:100%;">
                            <div>
                                <input type="text" name="search" class="form-control flex-grow-1"
                                    placeholder="Search users..." value="{{ request('search') }}"
                                    style="min-width: 200px; max-width: 300px;">
                            </div>

                            <div>
                                <select name="perPage" class="form-select" onchange="this.form.submit()"
                                    style="min-width: 120px; width: auto;">
                                    @foreach ([10, 20, 30, 50] as $limit)
                                        <option value="{{ $limit }}"
                                            {{ request('perPage', 10) == $limit ? 'selected' : '' }}>
                                            Show {{ $limit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>

                            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                                data-bs-target="#createUserModal">
                                Add User
                            </button>
                        </form>
                    </div>

                    <!-- Table wrapper: scroll vertical + horizontal -->
                    <div class="table-wrapper"
                        style="max-height: 500px; overflow-y: auto; overflow-x: auto; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                        <table class="table table-bordered table-hover align-middle text-center mb-0"
                            style="min-width: 900px;">
                            <thead style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $key => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>******</td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $user->getRoleNames()->first() ?? 'None' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if (Auth::id() !== $user->id)
                                                <form action="{{ route('superadmin.users.toggle-role', $user->id) }}"
                                                    method="POST" class="d-inline toggle-role-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                        title="Toggle Role">
                                                        @if ($user->hasRole('customer'))
                                                            <i class="fas fa-user-shield"></i> Make Admin
                                                        @else
                                                            <i class="fas fa-user"></i> Make Customer
                                                        @endif
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted"><i class="fas fa-user-check"></i> You</span>
                                            @endif

                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $user->id }}" title="Edit User">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <form method="POST"
                                                action="{{ route('superadmin.users.destroy', $user->id) }}"
                                                class="d-inline delete-form" id="delete-form-{{ $user->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $user->id }}" title="Delete User">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </td>

                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST"
                                                action="{{ route('superadmin.users.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Email</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Role</label>
                                                            <select name="role" class="form-select">
                                                                @foreach (['super_admin', 'admin', 'writer', 'customer'] as $roleOption)
                                                                    <option value="{{ $roleOption }}"
                                                                        @if ($user->hasRole($roleOption)) selected @endif>
                                                                        {{ ucfirst($roleOption) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>New Password (optional)</label>
                                                            <input type="password" name="password" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Confirm Password</label>
                                                            <input type="password" name="password_confirmation"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Save
                                                            Changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('superadmin.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-select" required>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="writer">Writer</option>
                                <option value="customer" selected>Customer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let userId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This user will be deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + userId).submit();
                        }
                    });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
