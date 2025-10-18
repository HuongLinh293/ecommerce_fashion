{{-- Admin Dashboard --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Admin VIVILLAN')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl">Dashboard</h1>
        <p class="text-sm text-gray-600 mt-1">Tổng quan hoạt động kinh doanh</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Revenue --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Doanh thu</p>
                    <p class="text-2xl mt-2">{{ number_format($stats['revenue'], 0, ',', '.') }}₫</p>
                    <p class="text-xs mt-2 {{ $stats['revenueChange'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['revenueChange'] > 0 ? '+' : '' }}{{ $stats['revenueChange'] }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đơn hàng</p>
                    <p class="text-2xl mt-2">{{ number_format($stats['orders']) }}</p>
                    <p class="text-xs mt-2 {{ $stats['ordersChange'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['ordersChange'] > 0 ? '+' : '' }}{{ $stats['ordersChange'] }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Khách hàng</p>
                    <p class="text-2xl mt-2">{{ number_format($stats['customers']) }}</p>
                    <p class="text-xs mt-2 {{ $stats['customersChange'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['customersChange'] > 0 ? '+' : '' }}{{ $stats['customersChange'] }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sản phẩm</p>
                    <p class="text-2xl mt-2">{{ number_format($stats['products']) }}</p>
                    <p class="text-xs mt-2 text-gray-600">Đang hoạt động</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Chart --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Thống kê doanh thu</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div></div>
                    <form method="GET" id="revenuePeriodForm" class="flex items-center gap-2">
                        <label for="period" class="text-sm text-gray-600">Khoảng:</label>
                        <select name="period" id="period" class="border px-2 py-1 rounded" onchange="document.getElementById('revenuePeriodForm').submit()">
                            <option value="day" {{ request('period', 'day') === 'day' ? 'selected' : '' }}>Ngày (7 ngày)</option>
                            <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>Tuần (12 tuần)</option>
                            <option value="month" {{ request('period') === 'month' ? 'selected' : '' }}>Tháng (12 tháng)</option>
                        </select>
                    </form>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart" style="width:100%;height:100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Order Status Chart --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Phân bổ trạng thái đơn hàng</h2>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="orderStatusChart" style="width:100%;height:100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales & Categories Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Category Sales Chart --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Doanh số theo danh mục</h2>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="categorySalesChart" style="width:100%;height:100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment Methods Chart --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Phương thức thanh toán</h2>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="paymentMethodChart" style="width:100%;height:100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Orders --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Đơn hàng gần đây</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex-1">
                                <p class="text-sm">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-600">{{ $order->customer_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm">{{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.orders.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800 mt-4">
                    Xem tất cả →
                </a>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg">Sản phẩm bán chạy</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($topProducts as $index => $product)
                        <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-gray-600">Đã bán: {{ $product->order_items_count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.products.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800 mt-4">
                    Xem tất cả →
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Chart - Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData->pluck('label')) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueData->pluck('revenue')) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
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
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value) + 'đ';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Order Status Chart - Doughnut Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xác nhận', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [
                    {{ $ordersByStatus['pending'] ?? 0 }},
                    {{ $ordersByStatus['processing'] ?? 0 }},
                    {{ $ordersByStatus['shipped'] ?? 0 }},
                    {{ $ordersByStatus['completed'] ?? 0 }},
                    {{ $ordersByStatus['cancelled'] ?? 0 }}
                ],
                backgroundColor: [
                    '#fbbf24',
                    '#3b82f6',
                    '#6366f1',
                    '#10b981',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Category Sales Chart - Bar Chart
    const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
    new Chart(categorySalesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categorySales->pluck('category')->map(function($cat) {
                return match($cat) {
                    'men' => 'Nam',
                    'women' => 'Nữ',
                    'accessories' => 'Phụ kiện',
                    default => $cat
                };
            })) !!},
            datasets: [{
                label: 'Doanh số (VNĐ)',
                data: {!! json_encode($categorySales->pluck('total')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(249, 115, 22, 0.8)'
                ],
                borderRadius: 8,
                borderSkipped: false
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
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value) + 'đ';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Payment Method Chart - Pie Chart
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(paymentMethodCtx, {
        type: 'pie',
        data: {
            labels: ['COD', 'Momo', 'VNPay', 'Visa'],
            datasets: [{
                data: [
                    {{ $paymentMethods['cod'] ?? 0 }},
                    {{ $paymentMethods['momo'] ?? 0 }},
                    {{ $paymentMethods['vnpay'] ?? 0 }},
                    {{ $paymentMethods['visa'] ?? 0 }}
                ],
                backgroundColor: [
                    '#8b5cf6',
                    '#ec4899',
                    '#06b6d4',
                    '#f59e0b'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
