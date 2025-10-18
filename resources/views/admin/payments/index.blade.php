{{-- Admin Payments List --}}
@extends('layouts.admin')

@section('title', 'Quản Lý Thanh Toán - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl uppercase tracking-[0.2em] mb-2">Thanh toán</h1>
            <p class="text-sm text-gray-600">Quản lý và theo dõi các giao dịch thanh toán</p>
        </div>
        <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Xuất báo cáo
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Tổng giao dịch</p>
            <p class="text-2xl">{{ number_format($stats['total_amount'], 0, ',', '.') }}₫</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['total_count']) }} giao dịch</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Momo</p>
            <p class="text-2xl">{{ number_format($stats['momo_amount'], 0, ',', '.') }}₫</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['momo_count']) }} giao dịch</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">VNPay</p>
            <p class="text-2xl">{{ number_format($stats['vnpay_amount'], 0, ',', '.') }}₫</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['vnpay_count']) }} giao dịch</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">COD</p>
            <p class="text-2xl">{{ number_format($stats['cod_amount'], 0, ',', '.') }}₫</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['cod_count']) }} giao dịch</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <form method="GET" class="flex flex-wrap gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Tìm mã giao dịch, mã đơn hàng..." 
                value="{{ request('search') }}"
                class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg"
            >
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Lọc</button>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Mã giao dịch</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Mã đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Phương thức</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Số tiền</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Thời gian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs">{{ $payment->transaction_id ?? '-' }}</td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $payment->order->order_number }}</td>
                        <td class="px-6 py-4 text-sm">{{ $payment->order->customer_name }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded flex items-center justify-center text-white text-[10px]
                                    @if($payment->method === 'cod') bg-green-600
                                    @elseif($payment->method === 'momo') bg-purple-600
                                    @elseif($payment->method === 'vnpay') bg-blue-600
                                    @else bg-blue-800
                                    @endif">
                                    {{ substr(strtoupper($payment->method), 0, 1) }}
                                </div>
                                <span class="text-sm">{{ strtoupper($payment->method) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ number_format($payment->amount, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if(in_array($payment->status, ['completed', 'paid'])) bg-green-100 text-green-800
                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->status === 'refunded') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $payment->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Không có giao dịch nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Payment Methods Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-base uppercase tracking-wide mb-4">Phân bố thanh toán</h3>
            <div class="space-y-4">
                @foreach($paymentDistribution as $item)
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['method'] }}</span>
                            <span>{{ number_format($item['amount'], 0, ',', '.') }}₫</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $item['color'] }}" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500">{{ $item['percentage'] }}% tổng doanh thu</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-base uppercase tracking-wide mb-4">Giao dịch gần đây</h3>
            <div class="space-y-4">
                @foreach($recentPayments as $payment)
                    <div class="flex items-center justify-between pb-4 border-b last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded flex items-center justify-center text-white text-[10px]
                                @if($payment->method === 'cod') bg-green-600
                                @elseif($payment->method === 'momo') bg-purple-600
                                @elseif($payment->method === 'vnpay') bg-blue-600
                                @else bg-blue-800
                                @endif">
                                {{ substr(strtoupper($payment->method), 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm">{{ $payment->order->customer_name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm">{{ $payment->amount ? number_format($payment->amount, 0, ',', '.') . '₫' : '-' }}</p>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if(in_array($payment->status, ['completed', 'paid'])) bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $payment->status_label }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $payments->links('components.pagination') }}
    </div>
</div>
@endsection
