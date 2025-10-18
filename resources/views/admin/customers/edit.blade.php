{{-- Admin Customer Edit --}}
@extends('layouts.admin')

@section('title', 'Chỉnh sửa khách hàng - Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Chỉnh sửa khách hàng</h1>
        <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Quay lại</a>
    </div>

    <div class="bg-white p-6 rounded border border-gray-200">
        <form action="{{ route('admin.customers.update', $customer->id ?? $user->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs mb-1">Họ tên</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name ?? $user->name ?? '') }}" class="w-full px-3 py-2 border" required>
                </div>

                <div>
                    <label class="block text-xs mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email ?? $user->email ?? '') }}" class="w-full px-3 py-2 border">
                </div>

                <div>
                    <label class="block text-xs mb-1">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? $user->phone ?? '') }}" class="w-full px-3 py-2 border">
                </div>

                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_vip" value="1" {{ (old('is_vip', $customer->is_vip ?? false) ? 'checked' : '') }}>
                        <span class="text-sm">Đánh dấu VIP</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection