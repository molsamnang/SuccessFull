<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route($role . '.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $customer->id }}">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <!-- Name -->
                    <div class="mb-3 col-md-6">
                        <label for="name{{ $customer->id }}" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name{{ $customer->id }}" value="{{ $customer->name }}" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3 col-md-6">
                        <label for="email{{ $customer->id }}" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email{{ $customer->id }}" value="{{ $customer->email }}" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3 col-md-6">
                        <label for="phone{{ $customer->id }}" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone{{ $customer->id }}" value="{{ $customer->phone }}">
                    </div>

                    <!-- Gender -->
                    <div class="mb-3 col-md-6">
                        <label for="gender{{ $customer->id }}" class="form-label">Gender</label>
                        <select class="form-select" name="gender" id="gender{{ $customer->id }}">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $customer->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="mb-3 col-md-6">
                        <label for="password{{ $customer->id }}" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password{{ $customer->id }}">
                        <small class="text-muted">Leave blank to keep the same password</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3 col-md-6">
                        <label for="password_confirmation{{ $customer->id }}" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation{{ $customer->id }}">
                    </div>

                    <!-- Profile Image -->
                    <div class="mb-3 col-md-6">
                        <label for="profile_image{{ $customer->id }}" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="profile_image" id="profile_image{{ $customer->id }}">
                        @if ($customer->profile_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile" width="80">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
