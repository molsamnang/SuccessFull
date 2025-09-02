<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $post->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route($role . '.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $post->id }}">Edit Post #{{ $post->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    {{-- Validation Errors --}}
                    @if ($errors->any() && session('edit_post_id') == $post->id)
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="mb-3 col-md-12">
                        <label for="content{{ $post->id }}" class="form-label">Content</label>
                        <textarea name="content" id="content{{ $post->id }}" class="form-control" rows="3" required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 col-md-6">
                        <label for="status{{ $post->id }}" class="form-label">Status</label>
                        <select name="status" id="status{{ $post->id }}" class="form-select" required>
                            <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <!-- Customer -->
                    <div class="mb-3 col-md-6">
                        <label for="customer_id{{ $post->id }}" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id{{ $post->id }}" class="form-select" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $post->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="mb-3 col-md-6">
                        <label for="category_id{{ $post->id }}" class="form-label">Category</label>
                        <select name="category_id" id="category_id{{ $post->id }}" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Images -->
                    @if ($post->images && is_array($post->images))
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Current Images</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($post->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Image" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images -->
                    <div class="mb-3 col-md-12">
                        <label for="images{{ $post->id }}" class="form-label">Upload New Images</label>
                        <input type="file" name="images[]" id="images{{ $post->id }}" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Uploading new images will replace old ones.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
