@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('_', '', $role); // normalize "super_admin" → "superadmin"
@endphp

@extends('layouts.app')

@section('data_one')
<div class="container">
    <h3>Create New Post</h3>
    <form method="POST" action="{{ route($role . '.posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control">
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Poster Head</label>
            <input type="text" name="poster_head" class="form-control">
        </div>
        <div class="mb-3">
            <label>Poster Sizes (comma separated)</label>
            <input type="text" name="poster_sizes" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
