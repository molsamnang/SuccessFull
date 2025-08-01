@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('_', '', $role); // normalize "super_admin" → "superadmin"
@endphp
@extends('layouts.app')

@section('data_one')
    <div class="container">
        <h3 class="mb-3">Posts List</h3>
        <a href="{{ route($role . '.posts.create') }}" class="btn btn-primary mb-3">+ Add Post</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Customer</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->slug }}</td>
                        <td>{{ ucfirst($post->status) }}</td>
                        <td>{{ $post->customer?->name ?? '-' }}</td>
                        <td>{{ $post->category?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route($role . '.posts.show', $post->id) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route($role . '.posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route($role . '.posts.destroy', $post->id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
