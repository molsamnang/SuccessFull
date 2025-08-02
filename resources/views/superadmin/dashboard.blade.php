@extends('layouts.app')

@section('data_one')
    <div class="container mt-4">
        <!-- Cards Section -->
        <div class="row row-card-no-pd">
            <!-- Post Card -->
            <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><b>Total Posts</b></h6>
                                <p class="text-muted">All Posts Count</p>
                            </div>
                            <h4 class="text-info fw-bold">{{ $postsCount }}</h4>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info w-75"></div>
                        </div>
                        <a href="{{ route('superadmin.posts.index') }}" class="btn btn-sm btn-outline-info mt-2">Manage
                            Posts</a>
                    </div>
                </div>
            </div>

            <!-- Customer Card -->
            <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><b>Total Customers</b></h6>
                                <p class="text-muted">All Customers Count</p>
                            </div>
                            <h4 class="text-success fw-bold">{{ $customersCount }}</h4>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success w-60"></div>
                        </div>
                        <a href="{{ route('superadmin.customers.index') }}"
                            class="btn btn-sm btn-outline-success mt-2">Manage Customers</a>
                    </div>
                </div>
            </div>

            <!-- Category Card -->
            <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><b>Total Categories</b></h6>
                                <p class="text-muted">All Categories Count</p>
                            </div>
                            <h4 class="text-warning fw-bold">{{ $categoriesCount }}</h4>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-warning w-40"></div>
                        </div>
                        <a href="{{ route('superadmin.categories.index') }}"
                            class="btn btn-sm btn-outline-warning mt-2">Manage Categories</a>
                    </div>
                </div>
            </div>

            <!-- Comment Card -->
            <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><b>Total Comments</b></h6>
                                <p class="text-muted">All Comments Count</p>
                            </div>
                            <h4 class="text-danger fw-bold">{{ $commentsCount }}</h4>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-danger w-50"></div>
                        </div>
                        <a href="{{ route('superadmin.comments.index') }}" class="btn btn-sm btn-outline-danger mt-2">Manage
                            Comments</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <!-- Chart Section -->
        <div class="card mt-5 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold">Overview Chart</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="dataChart"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartData = {
                posts: {{ $postsCount }},
                customers: {{ $customersCount }},
                categories: {{ $categoriesCount }},
                comments: {{ $commentsCount }}
            };

            const ctx = document.getElementById('dataChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Posts', 'Customers', 'Categories', 'Comments'],
                    datasets: [{
                        label: 'Total Count',
                        data: [
                            chartData.posts,
                            chartData.customers,
                            chartData.categories,
                            chartData.comments
                        ],
                        backgroundColor: [
                            'rgba(23, 162, 184, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(220, 53, 69, 0.7)'
                        ],
                        borderColor: [
                            'rgba(23, 162, 184, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' items';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
