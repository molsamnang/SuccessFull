{{-- Edit Modal Partial for Restaurants --}}
@foreach ($restaurants as $restaurant)
<div class="modal fade" id="editModal{{ $restaurant->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $restaurant->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route($role . '.restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $restaurant->id }}">Edit Restaurant #{{ $restaurant->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    {{-- Validation Errors --}}
                    @if ($errors->any() && session('edit_restaurant_id') == $restaurant->id)
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
                        <label for="name{{ $restaurant->id }}" class="form-label">Name</label>
                        <input type="text" name="name" id="name{{ $restaurant->id }}" class="form-control" value="{{ old('name', $restaurant->name) }}" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3 col-md-6">
                        <label for="phone{{ $restaurant->id }}" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone{{ $restaurant->id }}" class="form-control" value="{{ old('phone', $restaurant->phone) }}">
                    </div>

                    <!-- Location -->
                    <div class="mb-3 col-md-6">
                        <label for="location{{ $restaurant->id }}" class="form-label">Location</label>
                        <input type="text" name="location" id="location{{ $restaurant->id }}" class="form-control" value="{{ old('location', $restaurant->location) }}">
                    </div>

                    <!-- Address -->
                    <div class="mb-3 col-md-6">
                        <label for="address{{ $restaurant->id }}" class="form-label">Address</label>
                        <input type="text" name="address" id="address{{ $restaurant->id }}" class="form-control" value="{{ old('address', $restaurant->address) }}">
                    </div>

                    <!-- Description -->
                    <div class="mb-3 col-md-12">
                        <label for="description{{ $restaurant->id }}" class="form-label">Description</label>
                        <textarea name="description" id="description{{ $restaurant->id }}" class="form-control" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                    </div>

                    <!-- Current Images -->
                    @if ($restaurant->images && is_array($restaurant->images))
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Current Images</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($restaurant->images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Images -->
                    <div class="mb-3 col-md-12">
                        <label for="images{{ $restaurant->id }}" class="form-label">Upload New Images</label>
                        <input type="file" name="images[]" id="images{{ $restaurant->id }}" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Uploading new images will replace old ones.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Restaurant</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
