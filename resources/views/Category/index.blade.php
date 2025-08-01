@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp

@extends('layouts.app')

@section('data_one')
    <div class="container mt-3 alert alert-primary">
        <!-- Add Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Category
        </button>

        <!-- Category Table -->
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th width="350">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <!-- Show -->
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#showModal{{ $cat->id }}">
                                <i class="fas fa-eye"></i> Show
                            </button>

                            <!-- Edit -->
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $cat->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- Delete -->
                            <form action="{{ route($role . '.categories.destroy', $cat->id) }}" method="POST"
                                style="display:inline-block;" onsubmit="return confirmDelete(this);">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route($role . '.categories.store') }}" method="POST" class="modal-content">
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

    <!-- Show & Edit Modals -->
    @foreach ($categories as $cat)
        <!-- Show Modal -->
        <div class="modal fade" id="showModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title">Category Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> {{ $cat->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{route($role . '.categories.update', $cat->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to recover this category!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush
