@extends('home')

@section('data_one')
    <div class="row d-flex ">
        <div class="col-12">
            <div class="container mt-2">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add
                    Customer</button>

                <!-- Customer Table -->
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    <!-- Show Modal Button -->
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                        data-bs-target="#showModal{{ $customer->id }}">
                                        <i class="fas fa-eye"></i> Show
                                    </button>

                                    <!-- Edit Modal Button -->
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $customer->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>

                            </tr>

                            <!-- Show Modal -->
                            <div class="modal fade" id="showModal{{ $customer->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Customer Info</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Name:</strong> {{ $customer->name }}</p>
                                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                                            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="text" name="name" class="form-control mb-2"
                                                    value="{{ $customer->name }}" required>
                                                <input type="email" name="email" class="form-control mb-2"
                                                    value="{{ $customer->email }}" required>
                                                <input type="text" name="phone" class="form-control mb-2"
                                                    value="{{ $customer->phone }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                {{ $customers->links() }}
            </div>

            <!-- Create Modal -->
            <div class="modal fade" id="createModal" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Customer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                <input type="text" name="phone" class="form-control mb-2" placeholder="Phone">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Customer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
