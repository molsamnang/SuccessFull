@extends('home')

@section('data_one')
    <div class="container mt-3 ">
        <!-- Create Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Category
        </button>
        <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"
                aria-haspopup="true">
                <i class="fa fa-search"></i>
            </a>
            <ul class="dropdown-menu dropdown-search animated fadeIn">
                <form class="navbar-left navbar-form nav-search">
                    <div class="input-group">
                        <input type="text" placeholder="Search ..." class="form-control" />
                    </div>
                </form>
            </ul>
        </li>
        <!-- Category Table -->
        <table class="table  ">
            <thead class="bg-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th width="300">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <!-- Show Modal Button -->
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#showModal{{ $cat->id }}">
                                <i class="fas fa-eye"></i> Show
                            </button>

                            <!-- Edit Modal Button -->
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $cat->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('Category.destroy', $cat->id) }}" method="POST"
                                style="display:inline-block;">

                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>


                    </tr>
                @endforeach
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
            <form action="{{ route('Category.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Category name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Show & Edit Modals Outside Table -->
    @foreach ($categories as $cat)
        <!-- Show Modal -->
        <div class="modal fade" id="showModal{{ $cat->id }}" tabindex="-1"
            aria-labelledby="showModalLabel{{ $cat->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModalLabel{{ $cat->id }}">Category Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> {{ $cat->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1"
            aria-labelledby="editModalLabel{{ $cat->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('Category.update', $cat->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $cat->id }}">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2" value="{{ $cat->name }}"
                            required>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This category will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush
