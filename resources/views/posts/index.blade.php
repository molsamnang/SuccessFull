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
        <h2>Posts Management</h2>

        {{-- Search & Per Page --}}
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search posts content..."
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
                Add Post
            </button>
        </form>

        {{-- Posts Table --}}
        <div class="table-responsive"
            style="max-height: 600px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th>#</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Customer</th>
                        <th>Category</th>
                        <th>Images</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $key => $post)
                        <tr>
                            <td>{{ $posts->firstItem() + $key }}</td>
                            <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $post->content }}
                            </td>
                            <td>{{ ucfirst($post->status) }}</td>
                            <td>{{ $post->customer->name ?? '-' }}</td>
                            <td>{{ $post->category->name ?? '-' }}</td>
                            <td>
                                @if (!empty($post->images) && is_array($post->images))
                                    @foreach (array_slice($post->images, 0, 3) as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Image"
                                            class="img-thumbnail me-1" style="height:50px; width:50px; object-fit:cover;">
                                    @endforeach
                                    @if (count($post->images) > 3)
                                        <span>+{{ count($post->images) - 3 }}</span>
                                    @endif
                                @else
                                    <span>No images</span>
                                @endif
                            </td>
                            <td>{{ $post->created_at->format('d-m-Y') }}</td>
                            <td>
                                {{-- Show --}}
                                <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal"
                                    data-bs-target="#showModal{{ $post->id }}" title="Show">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Edit --}}
                                <button type="button" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $post->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route($role . '.posts.destroy', $post->id) }}" method="POST"
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
                        <div class="modal fade" id="showModal{{ $post->id }}" tabindex="-1"
                            aria-labelledby="showModalLabel{{ $post->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="showModalLabel{{ $post->id }}">Post Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Content:</strong> {{ $post->content }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>
                                        <p><strong>Customer:</strong> {{ $post->customer->name ?? '-' }}</p>
                                        <p><strong>Category:</strong> {{ $post->category->name ?? '-' }}</p>
                                        <p><strong>Created At:</strong> {{ $post->created_at->format('d-m-Y H:i') }}</p>

                                        <p><strong>Images:</strong></p>
                                        @if (!empty($post->images) && is_array($post->images))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($post->images as $img)
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Post Image"
                                                        class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No images uploaded.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                     @include('posts.edit')
                    @empty
                        <tr>
                            <td colspan="8">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route($role . '.posts.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Validation Errors --}}
                    @if ($errors->any() && !session('edit_post_id'))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" accept="image/*"
                            multiple>
                        <small class="text-muted">You can upload one or multiple images.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Post</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    {{-- Auto-show modals on validation errors --}}
    @if ($errors->any())
        @if (session('edit_post_id'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session('edit_post_id') }}'));
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
