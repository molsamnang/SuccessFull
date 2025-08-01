@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Comment Details</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Comment by: {{ $comment->customer->name ?? 'N/A' }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">On Post: {{ $comment->post->title ?? 'N/A' }}</h6>
            <p class="card-text">{{ $comment->body }}</p>

            <p class="text-muted">
                Created at: {{ $comment->created_at->format('d M Y, H:i') }} <br>
                Updated at: {{ $comment->updated_at->format('d M Y, H:i') }}
            </p>

            <a href="{{ route('comments.index') }}" class="btn btn-secondary">Back to Comments</a>
        </div>
    </div>
</div>
@endsection
