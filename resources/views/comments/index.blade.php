@extends('layouts.app')

@section('data_one')
    @php
        $role = strtolower(auth()->user()->roles->pluck('name')->first() ?? '');
        $role = str_replace('super_admin', 'superadmin', $role);
    @endphp

    <div class="container mt-4">
        <h3 class="mb-4">Comments Management</h3>

        <!-- Flash Success -->
        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            </script>
        @endif

        <!-- Add Comment Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCommentModal">
            <i class="fas fa-plus me-1"></i> Add Comment
        </button>

        @if ($comments->count())
            <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>#</th>
                            <th>Post</th>
                            <th>Commenter</th>
                            <th>Body</th>
                            <th>Created At</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $idx => $comment)
                            <tr>
                                <td>{{ $comments->firstItem() + $idx }}</td>
                                <td>{{ $comment->post->content ?? 'N/A' }}</td>
                                <td>
                                    @if ($comment->user)
                                        <span class="badge bg-primary">{{ $comment->user->name }}</span>
                                    @elseif($comment->customer)
                                        <span class="badge bg-success">{{ $comment->customer->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($comment->body, 50) }}</td>
                                <td>{{ $comment->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td>
                                    <!-- Show -->
                                    <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal"
                                        data-bs-target="#showCommentModal{{ $comment->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Edit -->
                                    <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal"
                                        data-bs-target="#editCommentModal{{ $comment->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Delete -->
                                    <form action="{{ route($role . '.comments.destroy', $comment->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Show Modal -->
                            <div class="modal fade" id="showCommentModal{{ $comment->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content p-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Comment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <p><strong>Post:</strong> {{ $comment->post->content ?? 'N/A' }}</p>
                                            <p><strong>Commenter:</strong>
                                                {{ $comment->user->name ?? ($comment->customer->name ?? 'Unknown') }}</p>
                                            <p><strong>Comment:</strong> {{ $comment->body }}</p>
                                            <p><strong>Created At:</strong> {{ $comment->created_at }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <!-- Edit Comment Modal -->
                            <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1"
                                aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route($role . '.comments.update', $comment->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Edit
                                                    Comment #{{ $comment->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body row">
                                                {{-- Validation Errors --}}
                                                @if ($errors->any() && session('edit_comment_id') == $comment->id)
                                                    <div class="alert alert-danger col-12">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <!-- Comment Body -->
                                                <div class="mb-3 col-md-12">
                                                    <label for="body{{ $comment->id }}" class="form-label">Comment</label>
                                                    <textarea name="body" id="body{{ $comment->id }}" class="form-control" rows="4" required>{{ old('body', $comment->body) }}</textarea>
                                                </div>

                                                <!-- Optionally: Post Selection -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="post_id{{ $comment->id }}" class="form-label">Post</label>
                                                    <select name="post_id" id="post_id{{ $comment->id }}"
                                                        class="form-select" required>
                                                        @foreach ($posts as $post)
                                                            <option value="{{ $post->id }}"
                                                                {{ old('post_id', $comment->post_id) == $post->id ? 'selected' : '' }}>
                                                                {{ $post->content }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Optionally: Commenter Type (User/Customer) -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="commenter_type{{ $comment->id }}"
                                                        class="form-label">Commenter Type</label>
                                                    <select name="commenter_type" id="commenter_type{{ $comment->id }}"
                                                        class="form-select">
                                                        <option value="user" {{ $comment->user_id ? 'selected' : '' }}>
                                                            User</option>
                                                        <option value="customer"
                                                            {{ $comment->customer_id ? 'selected' : '' }}>Customer</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Update Comment</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $comments->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info">No comments found.</div>
        @endif
    </div>

    <!-- Create Comment Modal -->
    <div class="modal fade" id="createCommentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route($role . '.comments.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Comment</h5>
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This comment will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
