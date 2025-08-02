@php
    $role = strtolower(auth()->user()->roles->pluck('name')->first() ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp

@extends('home')

@section('data_one')
    <div class="container mt-4">
        <h2 class="mb-4">Comments Management</h2>

        <!-- Success Message -->
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            </script>
        @endif

        <!-- Add Comment Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCommentModal">
            Add New Comment
        </button>

        <!-- Comments Table -->
        @if ($comments->count())
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Post</th>
                        <th>Customer</th>
                        <th>Comment</th>
                        <th>F</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $comment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $comment->post->title }}</td>
                            <td>
                                @if (!empty($comment->user->name))
                                    {{ $comment->user->name }}
                                @elseif (!empty($comment->customer->name))
                                    {{ $comment->customer->name }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <td>
                                @if ($comment->user->hasRole('super_admin'))
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($comment->user->hasRole('admin'))
                                    <span class="badge bg-primary">Admin</span>
                                @elseif($comment->user->hasRole('writer'))
                                    <span class="badge bg-warning text-dark">Writer</span>
                                @elseif($comment->user->hasRole('customer'))
                                    <span class="badge bg-secondary">Customer</span>
                                @else
                                    <span class="badge bg-light text-dark">User</span>
                                @endif
                            </td>
                            <td>{{ $comment->body }}</td>
                            <td>{{ $comment->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>
                                <!-- Show Modal Trigger -->
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#showCommentModal{{ $comment->id }}">Show</button>

                                <!-- Edit Modal Trigger -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editCommentModal{{ $comment->id }}">Edit</button>

                                <!-- Delete -->
                                <form action="{{ route($role . '.comments.destroy', $comment->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Show Modal -->
                        <div class="modal fade" id="showCommentModal{{ $comment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Comment Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Post:</strong> {{ $comment->post->title }}</p>
                                        <p><strong>Customer:</strong> {{ $comment->customer->name }}</p>
                                        <p><strong>Comment:</strong> {{ $comment->body }}</p>
                                        <p><strong>Created At:</strong> {{ $comment->created_at }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route($role . '.comments.update', $comment->id) }}" method="POST"
                                    class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Comment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="body" class="form-label">Comment</label>
                                            <textarea name="body" class="form-control" required>{{ $comment->body }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">No comments found.</div>
        @endif
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createCommentModal" tabindex="-1" aria-labelledby="createCommentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route($role . '.comments.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCommentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="post_id" class="form-label">Post</label>
                        <select name="post_id" id="post_id" class="form-select" required>
                            <option value="" disabled selected>-- Select Post --</option>
                            @foreach ($posts as $post)
                                <option value="{{ $post->id }}">{{ $post->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Comment</label>
                        <textarea name="body" id="body" class="form-control" rows="4" required>{{ old('body') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        <input type="hidden" name="customer_id" value="{{ Auth::id() }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This comment will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
            return false;
        }
    </script>
@endsection
