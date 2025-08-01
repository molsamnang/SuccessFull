@extends('layouts.app')
@section('data_one')
    <div class="container">
        <div class="alert alert-primary">
            <div class="container mt-4">
                <div class="container mt-4">
                    <h1 class="mb-4">Super Admin Dashboard</h1>

                    <div class="row">
                        <!-- Posts Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card text-white bg-primary h-100">
                                <div class="card-header">Posts</div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h2 class="card-title">{{ $postsCount }}</h2>
                                    <a href="{{ route('superadmin.posts.index') }}" class="btn btn-light btn-sm mt-3">Manage
                                        Posts</a>
                                </div>
                            </div>
                        </div>

                        <!-- Customers Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card text-white bg-success h-100">
                                <div class="card-header">Customers</div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h2 class="card-title">{{ $customersCount }}</h2>
                                    <a href="{{ route('superadmin.customers.index') }}"
                                        class="btn btn-light btn-sm mt-3">Manage Customers</a>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card text-white bg-warning h-100">
                                <div class="card-header">Categories</div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h2 class="card-title">{{ $categoriesCount }}</h2>
                                    <a href="{{ route('superadmin.categories.index') }}"
                                        class="btn btn-light btn-sm mt-3">Manage Categories</a>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card text-white bg-danger h-100">
                                <div class="card-header">Comments</div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h2 class="card-title">{{ $commentsCount }}</h2>
                                    <a href="{{ route('superadmin.comments.index') }}"
                                        class="btn btn-light btn-sm mt-3">Manage Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
