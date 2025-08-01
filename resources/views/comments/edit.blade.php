@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Comment</h2>

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="customer" class="form-label">Customer</label>
            <input type="text" id="customer" class="form-control" value="{{ $comment->customer->name ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label for="post" class="form-label">Post</label>
            <input type="text" id="post" class="form-control" value="{{ $comment->post->title ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Comment Body</label>
            <textarea name="body" id="body" class="form-control @error('body') is-invalid @enderror" rows="5" required>{{ old('body', $comment->body) }}</textarea>
            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Comment</button>
        <a href="{{ route('comments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
