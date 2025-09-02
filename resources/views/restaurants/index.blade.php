@extends('layouts.app')

@section('data_one')
    @php
        $role = strtolower(auth()->user()->role ?? '');
        $role = str_replace('super_admin', 'superadmin', $role);
    @endphp

    {{-- SweetAlert2 toast success notification --}}
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
        <h2>Restaurant Management</h2>

        {{-- Search & Per Page --}}
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search restaurants..."
                   value="{{ request('search') }}" style="max-width: 300px;">
            <select name="perPage" class="form-select" style="max-width: 120px;" onchange="this.form.submit()">
                @foreach ([10, 25, 50, 100] as $limit)
                    <option value="{{ $limit }}" {{ request('perPage', 10) == $limit ? 'selected' : '' }}>
                        Show {{ $limit }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filter</button>

            <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createModal">
                Add Restaurant
            </button>
        </form>

        {{-- Restaurants Table --}}
        <div class="table-responsive"
             style="max-height: 600px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        {{-- <th>Location</th> --}}
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $key => $restaurant)
                        <tr>
                            <td>{{ $restaurants->firstItem() + $key }}</td>
                            <td>{{ $restaurant->name }}</td>
                            <td>{{ $restaurant->phone ?? '-' }}</td>
                            {{-- <td>{{ $restaurant->location ?? '-' }}</td> --}}
                            <td>
                                @if (!empty($restaurant->images))
                                    @foreach (array_slice($restaurant->images, 0, 3) as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Image"
                                             class="img-thumbnail me-1"
                                             style="height:50px; width:50px; object-fit:cover;">
                                    @endforeach
                                    @if (count($restaurant->images) > 3)
                                        <span>+{{ count($restaurant->images) - 3 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">No images</span>
                                @endif
                            </td>
                            <td>
                                {{-- Show --}}
                                <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal"
                                        data-bs-target="#showModal{{ $restaurant->id }}" title="Show">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Edit --}}
                                <button type="button" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $restaurant->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route($role . '.restaurants.destroy', $restaurant->id) }}" method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Show Modal --}}
                        <div class="modal fade" id="showModal{{ $restaurant->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Restaurant Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Name:</strong> {{ $restaurant->name }}</p>
                                        <p><strong>Description:</strong> {{ $restaurant->description ?? '-' }}</p>
                                        <p><strong>Phone:</strong> {{ $restaurant->phone ?? '-' }}</p>
                                        <p><strong>Location:</strong> {{ $restaurant->location ?? '-' }}</p>
                                        <p><strong>Address:</strong> {{ $restaurant->address ?? '-' }}</p>
                                        <p><strong>Images:</strong></p>
                                        @if (!empty($restaurant->images))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($restaurant->images as $img)
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Restaurant Image"
                                                         class="img-thumbnail" style="height:100px; object-fit:cover;">
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No images uploaded.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                        @include('restaurants.edit')
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No restaurants found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $restaurants->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route($role . '.restaurants.store') }}" method="POST" enctype="multipart/form-data"
                  class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Restaurant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Validation Errors --}}
                    @if ($errors->any() && !session('edit_restaurant_id'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">You can upload multiple images.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert Delete Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This restaurant will be deleted!",
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

    {{-- Auto-show modals on validation errors --}}
    @if ($errors->any())
        @if (session('edit_restaurant_id'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session('edit_restaurant_id') }}'));
                    editModal.show();
                });
            </script>
        @else
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var createModal = new bootstrap.Modal(document.getElementById('createModal'));
                    createModal.show();
                });
            </script>
        @endif
    @endif
@endsection
