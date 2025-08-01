@extends('layouts.app')

@section('data_one')
@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp

<!-- Flash Message -->
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    </script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title">Customer Management</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus-circle me-1"></i> Add Customer
                </button>
            </div>

            <!-- Search Form -->
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="perPage" class="form-select" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $limit)
                            <option value="{{ $limit }}" {{ request('perPage', 10) == $limit ? 'selected' : '' }}>
                                Show {{ $limit }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
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
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->gender }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#showModal{{ $customer->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route("$role.customers.destroy", $customer->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Include Show and Edit Modals -->
                            {{-- @include('Customer.modal_show', ['customer' => $customer])
                            @include('Customer.modal_edit', ['customer' => $customer]) --}}
                        @empty
                            <tr>
                                <td colspan="7">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- @include('Customer.modal_create') <!-- Add Modal --> --}}

<!-- SweetAlert for Delete Confirmation -->
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This customer will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
