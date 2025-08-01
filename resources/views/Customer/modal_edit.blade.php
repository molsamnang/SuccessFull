<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route("$role.customers.update", $customer->id) }}" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ $customer->full_name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control" required>{{ $customer->address }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
