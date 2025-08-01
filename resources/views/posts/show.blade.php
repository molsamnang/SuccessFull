@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('_', '', $role); // normalize "super_admin" → "superadmin"
@endphp
@extends('layouts.app')

@section('data_one')
<div class="container mt-4">
    <h3 class="mb-4">Post Detail</h3>

    <div class="row">
        <!-- Left Column: Post Info -->
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item"><strong>Title:</strong> {{ $post_->title }}</li>
                <li class="list-group-item"><strong>Slug:</strong> {{ $post_->slug }}</li>
                <li class="list-group-item"><strong>Content:</strong><br> {!! nl2br(e($post_->content)) !!}</li>
                <li class="list-group-item"><strong>Poster Head:</strong> {{ $post_->poster_head }}</li>
                <li class="list-group-item"><strong>Poster Sizes:</strong> 
                    {{ is_array($post_->poster_sizes) ? implode(', ', $post_->poster_sizes) : '' }}
                </li>
                <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($post_->status) }}</li>
                <li class="list-group-item"><strong>Customer:</strong> {{ $post_->customer?->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Category:</strong> {{ $post_->category?->name ?? '-' }}</li>
            </ul>
        </div>

        <!-- Right Column: Image -->
        <div class="col-md-8 d-flex flex-column align-items-center justify-content-start">
            @if ($post_->image)
                <img 
                    src="{{ asset('storage/' . $post_->image) }}" 
                    alt="Post Image" 
                    class="img-fluid rounded shadow-sm" 
                    style="max-width: 100%; max-height: 400px; object-fit: contain;"
                >
            @else
                <p class="text-muted fst-italic">No image available</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route($role . '.posts.index') }}" class="btn btn-secondary">← Back to Posts</a>
    </div>
</div>
@endsection
