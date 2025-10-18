{{-- Admin Customers List --}}
@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng - Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl uppercase tracking-[0.2em] mb-2">Khách hàng</h1>
        <p class="text-sm text-gray-600">Quản lý thông tin khách hàng và lịch sử mua hàng</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Tổng khách hàng</p>
            <p class="text-2xl">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Khách hàng VIP</p>
            <p class="text-2xl text-yellow-600">{{ number_format($stats['vip']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Khách mới (tháng)</p>
            <p class="text-2xl text-green-600">{{ number_format($stats['new_month']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Tỷ lệ quay lại</p>
            <p class="text-2xl">{{ $stats['return_rate'] }}%</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <form method="GET" class="flex flex-wrap gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Tìm tên, email, số điện thoại..." 
                value="{{ request('search') }}"
                class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg"
            >
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Lọc</button>
        </form>
    </div>

    {{-- Customers Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Số điện thoại</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Tổng chi tiêu</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Ngày tham gia</th>
                    <th class="px-6 py-3 text-right text-xs uppercase tracking-wider text-gray-600">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center text-sm">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <span>{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $customer->email }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $customer->phone }}
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $customer->orders_count ?? ($customer->orders()->count() ?? 0) }}</td>
                        <td class="px-6 py-4">{{ number_format($customer->total_spent ?? ($customer->orders()->sum('total') ?? 0), 0, ',', '.') }}₫</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($customer->is_vip) bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $customer->is_vip ? 'VIP' : 'Hoạt động' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Không có khách hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $customers->links('components.pagination') }}
    </div>
</div>
@endsection
