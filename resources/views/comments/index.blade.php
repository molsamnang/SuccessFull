
@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp
@extends('home')
@section('data_one')

<div class="container mt-4">
    <h2>Comments List</h2>

    <!-- Add Comment Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCommentModal">
        Add New Comment
    </button>

    <!-- Success Flash -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($comments->count())
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Post</th>
                    <th>Customer</th>
                    <th>Comment</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $comment->post->title }}</td>
                        <td>{{ $comment->customer->name }}</td>
                        <td>{{ $comment->body }}</td>
                        <td>{{ $comment->created_at->format('Y-m-d') }}</td>
                        <td>
                            <!-- Show -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showCommentModal{{ $comment->id }}">
                                Show
                            </button>

                            <!-- Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                                Edit
                            </button>

                            <!-- Delete -->
                            <form action="{{ route($role . '.comments.destroy', $comment->id) }}" method="POST"
                                  style="display:inline;" onsubmit="return confirmDelete(event)">
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
                                    <h5 class="modal-title">Show Comment</h5>
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
                            <form action="{{ route($role . '.comments.update', $comment->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Comment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Comment</label>
                                            <textarea class="form-control" name="body" required>{{ $comment->body }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success">Update</button>
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCommentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route($role . '.comments.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Post</label>
                        <select name="post_id" class="form-control" required>
                            @foreach ($posts as $post)
                                <option value="{{ $post->id }}">{{ $post->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Comment</label>
                        <textarea name="body" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Submit</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert Delete Confirm -->
<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You cannot recover this comment!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!',
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
