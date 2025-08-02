@extends('layouts.app')

@section('data_one')
    @php
        $role = strtolower(auth()->user()->role ?? '');
        $role = str_replace('super_admin', 'superadmin', $role);
    @endphp

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    <div class="container">
        <div class="row" style="max-width:100%; ">
            <div class="col-12">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Customer Management</h4>

                    </div>

                    <!-- Search + PerPage Form -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0">
                            <div>
                                <input type="text" name="search" class="form-control flex-grow-1"
                                    placeholder="Search customers..." value="{{ request('search') }}"
                                    style="min-width: 200px; max-width: 300px;">
                            </div>

                            <div>
                                <select name="perPage" class="form-select" onchange="this.form.submit()"
                                    style="min-width: 120px; width: auto;">
                                    @foreach ([10, 25, 50, 100] as $limit)
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
                        </form>


                        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createModal">
                            Add Customer
                        </button>
                    </div>


                    <!-- Table wrapper: scroll vertical + horizontal -->
                    <div class="table-wrapper"
                        style="max-height: 500px; overflow-y: auto; overflow-x: auto; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                        <table class="table table-bordered table-hover align-middle text-center mb-0"
                            style="min-width: 900px;">
                            <thead class="" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>#</th>
                                    <th>Profile</th>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $key => $customer)
                                    <tr>
                                        <td>{{ $customers->firstItem() + $key }}</td>
                                        <td>
                                            @if ($customer->profile_image)
                                                <img src="{{ asset('storage/' . $customer->profile_image) }}" width="40"
                                                    height="40" class="rounded-circle" alt="Profile Image">
                                            @else
                                                <span>No image</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ ucfirst($customer->gender) }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#showModal{{ $customer->id }}">
                                                <i class="fas fa-eye"></i> Show
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $customer->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route("$role.customers.destroy", $customer->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger delete-btn" type="submit">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('Customer.modal_show', ['customer' => $customer])
                                    @include('Customer.modal_edit', ['customer' => $customer])
                                @empty
                                    <tr>
                                        <td colspan="8">No customers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Customer.modal_create')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This customer will be deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
