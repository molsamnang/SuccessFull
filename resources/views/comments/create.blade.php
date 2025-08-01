@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
@endphp
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Add New Comment</h2>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('comments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="post_id" class="form-label">Post</label>
            <select name="post_id" id="post_id" class="form-select" required>
                <option value="">-- Select Post --</option>
                @foreach ($posts as $post)
                    <option value="{{ $post->id }}" {{ old('post_id') == $post->id ? 'selected' : '' }}>
                        {{ $post->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" id="customer_id" class="form-select" required>
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Comment</label>
            <textarea name="body" id="body" rows="4" class="form-control" required>{{ old('body') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Comment</button>
        <a href="{{ route('comments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
