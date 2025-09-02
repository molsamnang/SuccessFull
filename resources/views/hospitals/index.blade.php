@extends('layouts.app')

@section('data_one')
@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp

<!-- SweetAlert2 toast success notification -->
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

<div class="container my-4">
    <h2>Hospitals Management</h2>

    <!-- Add Hospital Button -->
    <button type="button" class="btn btn-primary mb-3 ms-auto" data-bs-toggle="modal" data-bs-target="#createHospitalModal">
        Add Hospital
    </button>

    <!-- Hospitals Table -->
    <div class="table-responsive" style="max-height:600px; overflow-y:auto; border:1px solid #ddd; border-radius:5px;">
        <table class="table table-hover table-bordered align-middle text-center mb-0">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    {{-- <th>Location</th> --}}
                    <th>Address</th>
                    <th>Phone</th>
                    <th>User</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitals as $key => $hospital)
                <tr>
                    <td>{{ $hospitals->firstItem() + $key ?? $hospital->id }}</td>
                    <td>{{ $hospital->name }}</td>
                    {{-- <td>{{ $hospital->location ?? '-' }}</td> --}}
                    <td>{{ $hospital->address ?? '-' }}</td>
                    <td>{{ $hospital->phone ?? '-' }}</td>
                    <td>{{ $hospital->user->name ?? '-' }}</td>
                    <td>
                        @if(!empty($hospital->images) && is_array($hospital->images))
                            @foreach(array_slice($hospital->images, 0, 3) as $img)
                                <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail me-1" style="height:50px;width:50px;object-fit:cover;">
                            @endforeach
                            @if(count($hospital->images) > 3)
                                <span>+{{ count($hospital->images) - 3 }}</span>
                            @endif
                        @else
                            <span>No images</span>
                        @endif
                    </td>
                    <td>
                        <!-- Show -->
                        <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#showHospitalModal{{ $hospital->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Edit -->
                        <button type="button" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editHospitalModal{{ $hospital->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Delete -->
                        <form action="{{ route($role.'.hospitals.destroy', $hospital->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mb-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Show Modal -->
                <div class="modal fade" id="showHospitalModal{{ $hospital->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $hospital->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Description:</strong> {{ $hospital->description ?? '-' }}</p>
                                <p><strong>Location:</strong> {{ $hospital->location ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $hospital->address ?? '-' }}</p>
                                <p><strong>Phone:</strong> {{ $hospital->phone ?? '-' }}</p>
                                <p><strong>Images:</strong></p>
                                <div class="d-flex flex-wrap gap-2">
                                    @if(!empty($hospital->images))
                                        @foreach($hospital->images as $img)
                                            <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail" style="height:100px; object-fit:cover;">
                                        @endforeach
                                    @else
                                        <span>No images uploaded.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                @include('hospitals.edit')

                @empty
                <tr>
                    <td colspan="8">No hospitals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $hospitals->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

    <!-- Create Hospital Modal -->
    <div class="modal fade" id="createHospitalModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route($role.'.hospitals.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Hospital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label>Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Hospital</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Auto-show modals on validation errors -->
    @if ($errors->any())
        @if (session('edit_hospital_id'))
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editHospitalModal{{ session('edit_hospital_id') }}'));
                editModal.show();
            });
            </script>
        @else
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var createModal = new bootstrap.Modal(document.getElementById('createHospitalModal'));
                createModal.show();
            });
            </script>
        @endif
    @endif

@endsection
