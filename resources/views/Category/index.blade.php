@extends('layouts.app')

@section('data_one')
    <style>
        h4 {
            font-family: Khmer OS Battambang;
        }
    </style>
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


    <div class="container">
        <div class="row" style="max-width:100%;">
            <div class="col-12">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">ការគ្រប់គ្រងប្រភេទ</h4>
                    </div>

                    <!-- Search + PerPage Form -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0">
                            <div>
                                <input type="text" name="search" class="form-control" placeholder="Search categories..."
                                    value="{{ request('search') }}" style="min-width: 200px; max-width: 300px;">
                            </div>

                            <div>
                                <select name="perPage" class="form-select" onchange="this.form.submit()"
                                    style="min-width: 120px;">
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
                            <i class="fas fa-plus me-1"></i> Add Category
                        </button>
                    </div>


                    <!-- Table -->
                    <div class="table-wrapper"
                        style="max-height: 500px; overflow-y: auto; overflow-x: auto; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                        <table class="table table-bordered table-hover align-middle text-center mb-0"
                            style="min-width: 650px;">
                            <thead style="position: sticky; top: 0; z-index: 10;" class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th width="350">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $key => $cat)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $key }}</td>
                                        <td>{{ $cat->name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#showModal{{ $cat->id }}">
                                                <i class="fas fa-eye"></i> Show
                                            </button>

                                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $cat->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <form action="{{ route($role . '.categories.destroy', $cat->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger delete-btn" type="submit">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Show Modal -->
                                    <div class="modal fade" id="showModal{{ $cat->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content p-3">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Category Info</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Name:</strong> {{ $cat->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Modal -->
                                    @include('Category.edit', ['cat' => $cat, 'role' => $role])

                                @empty
                                    <tr>
                                        <td colspan="3">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route($role . '.categories.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Category name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This category will be deleted!',
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
