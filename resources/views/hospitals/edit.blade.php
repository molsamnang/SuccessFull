<!-- Edit Hospital Modal -->
<div class="modal fade" id="editHospitalModal{{ $hospital->id }}" tabindex="-1" aria-labelledby="editHospitalModalLabel{{ $hospital->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route($role . '.hospitals.update', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editHospitalModalLabel{{ $hospital->id }}">Edit Hospital #{{ $hospital->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    {{-- Validation Errors --}}
                    @if ($errors->any() && session('edit_hospital_id') == $hospital->id)
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="mb-3 col-md-6">
                        <label for="name{{ $hospital->id }}" class="form-label">Name</label>
                        <input type="text" name="name" id="name{{ $hospital->id }}" class="form-control" value="{{ old('name', $hospital->name) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3 col-md-6">
                        <label for="description{{ $hospital->id }}" class="form-label">Description</label>
                        <textarea name="description" id="description{{ $hospital->id }}" class="form-control">{{ old('description', $hospital->description) }}</textarea>
                    </div>

                    <!-- Location -->
                    <div class="mb-3 col-md-6">
                        <label for="location{{ $hospital->id }}" class="form-label">Location</label>
                        <input type="text" name="location" id="location{{ $hospital->id }}" class="form-control" value="{{ old('location', $hospital->location) }}">
                    </div>

                    <!-- Address -->
                    <div class="mb-3 col-md-6">
                        <label for="address{{ $hospital->id }}" class="form-label">Address</label>
                        <input type="text" name="address" id="address{{ $hospital->id }}" class="form-control" value="{{ old('address', $hospital->address) }}">
                    </div>

                    <!-- Phone -->
                    <div class="mb-3 col-md-6">
                        <label for="phone{{ $hospital->id }}" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone{{ $hospital->id }}" class="form-control" value="{{ old('phone', $hospital->phone) }}">
                    </div>

                    <!-- Current Images -->
                    @if ($hospital->images && is_array($hospital->images))
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Current Images</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($hospital->images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="Hospital Image" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images -->
                    <div class="mb-3 col-md-12">
                        <label for="images{{ $hospital->id }}" class="form-label">Upload New Images</label>
                        <input type="file" name="images[]" id="images{{ $hospital->id }}" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Uploading new images will replace old ones.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Hospital</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
