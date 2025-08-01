@extends('layouts.app')

@section('data_one')
    <div class="container mt-3 p-4"
        style="max-width: 900px; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px;">

        <h4 class="mb-4">Edit Post</h4>

        <form method="POST" action="{{ route($role . '.posts.update', $post_->id) }}" enctype="multipart/form-data"
            class="small">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left column -->
                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="{{ old('title', $post_->title) }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control form-control-sm"
                            value="{{ old('slug', $post_->slug) }}">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Content</label>
                        <textarea name="content" class="form-control form-control-sm" rows="5" required>{{ old('content', $post_->content) }}</textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Poster Head</label>
                        <input type="text" name="poster_head" class="form-control form-control-sm"
                            value="{{ old('poster_head', $post_->poster_head) }}">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Poster Sizes (comma separated)</label>
                        <input type="text" name="poster_sizes" class="form-control form-control-sm"
                            value="{{ old('poster_sizes', is_array($post_->poster_sizes) ? implode(',', $post_->poster_sizes) : '') }}">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="draft" {{ old('status', $post_->status) === 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="published" {{ old('status', $post_->status) === 'published' ? 'selected' : '' }}>
                                Published</option>
                        </select>
                    </div>
                </div>

                <!-- Right column -->
                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select form-select-sm" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $post_->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select form-select-sm" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $post_->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Image</label><br>
                        @if ($post_->image)
                            <img src="{{ asset('storage/' . $post_->image) }}" alt="Post Image"
                                style="max-height: 120px; border-radius: 4px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                        @else
                            <p class="text-muted fst-italic">No image uploaded.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Change Image</label>
                        <input type="file" name="image" class="form-control form-control-sm">
                    </div>

                    <div class="d-flex justify-content-start gap-2 mt-4">
                        <button type="submit" class="btn btn-sm btn-primary">Update Post</button>
                        <a href="{{ route($role . '.posts.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
