{{-- Admin Settings --}}
@extends('layouts.admin')

@section('title', 'Cài Đặt Hệ Thống - Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl uppercase tracking-[0.2em] mb-2">Cài đặt</h1>
        <p class="text-sm text-gray-600">Quản lý cấu hình và thiết lập hệ thống</p>
    </div>

    {{-- Tabs Navigation --}}
    <div 
        x-data="{ activeTab: 'general' }"
        class="space-y-6"
    >
        <div class="border-b border-gray-200">
            <nav class="flex gap-8">
                <button 
                    @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-2 px-1 border-b-2 text-sm uppercase tracking-wide"
                >
                    Chung
                </button>
                <button 
                    @click="activeTab = 'payment'"
                    :class="activeTab === 'payment' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-2 px-1 border-b-2 text-sm uppercase tracking-wide"
                >
                    Thanh toán
                </button>
                <button 
                    @click="activeTab = 'shipping'"
                    :class="activeTab === 'shipping' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-2 px-1 border-b-2 text-sm uppercase tracking-wide"
                >
                    Vận chuyển
                </button>
                <button 
                    @click="activeTab = 'notifications'"
                    :class="activeTab === 'notifications' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-2 px-1 border-b-2 text-sm uppercase tracking-wide"
                >
                    Thông báo
                </button>
            </nav>
        </div>

        {{-- General Settings --}}
        <div x-show="activeTab === 'general'" class="space-y-6">
            <form action="{{ route('admin.settings.update-general') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
                @csrf
                <div>
                    <h3 class="text-lg mb-4">Thông tin cửa hàng</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-2">Tên cửa hàng</label>
                            <input type="text" name="store_name" value="{{ config('app.name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Email</label>
                            <input type="email" name="store_email" value="info@vivillan.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Số điện thoại</label>
                            <input type="text" name="store_phone" value="1900 xxxx" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Website</label>
                            <input type="text" name="store_website" value="https://vivillan.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm mb-2">Địa chỉ</label>
                        <textarea name="store_address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">123 Đường ABC, Quận 1, TP. Hồ Chí Minh</textarea>
                    </div>
                </div>

                <hr>

                <div>
                    <h3 class="text-lg mb-4">Thiết lập chung</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Cho phép đăng ký</p>
                                <p class="text-xs text-gray-500">Cho phép khách hàng tạo tài khoản mới</p>
                            </div>
                            <input type="checkbox" name="allow_registration" checked class="w-4 h-4">
                        </div>
                        <hr>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Bảo trì website</p>
                                <p class="text-xs text-gray-500">Tạm khóa truy cập website cho khách hàng</p>
                            </div>
                            <input type="checkbox" name="maintenance_mode" class="w-4 h-4">
                        </div>
                        <hr>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Cho phép đánh giá</p>
                                <p class="text-xs text-gray-500">Khách hàng có thể đánh giá sản phẩm</p>
                            </div>
                            <input type="checkbox" name="allow_reviews" checked class="w-4 h-4">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>

        {{-- Payment Settings --}}
        <div x-show="activeTab === 'payment'" class="space-y-6" style="display: none;">
            <form action="{{ route('admin.settings.update-payment') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
                @csrf
                <div>
                    <h3 class="text-lg mb-4">Phương thức thanh toán</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border rounded">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-600 rounded flex items-center justify-center text-white">₫</div>
                                <div>
                                    <p class="text-sm">Tiền mặt (COD)</p>
                                    <p class="text-xs text-gray-500">Thanh toán khi nhận hàng</p>
                                </div>
                            </div>
                            <input type="checkbox" name="payment_cod" checked class="w-4 h-4">
                        </div>
                        <div class="flex items-center justify-between p-4 border rounded">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-600 rounded flex items-center justify-center text-white text-xs">M</div>
                                <div>
                                    <p class="text-sm">Ví điện tử Momo</p>
                                    <p class="text-xs text-gray-500">Thanh toán qua ví Momo</p>
                                </div>
                            </div>
                            <input type="checkbox" name="payment_momo" checked class="w-4 h-4">
                        </div>
                        <div class="flex items-center justify-between p-4 border rounded">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 rounded flex items-center justify-center text-white text-xs">VN</div>
                                <div>
                                    <p class="text-sm">VNPay</p>
                                    <p class="text-xs text-gray-500">Cổng thanh toán VNPay</p>
                                </div>
                            </div>
                            <input type="checkbox" name="payment_vnpay" checked class="w-4 h-4">
                        </div>
                        <div class="flex items-center justify-between p-4 border rounded">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-800 rounded flex items-center justify-center text-white text-xs">V</div>
                                <div>
                                    <p class="text-sm">Thẻ tín dụng/ghi nợ</p>
                                    <p class="text-xs text-gray-500">Visa, Mastercard, JCB</p>
                                </div>
                            </div>
                            <input type="checkbox" name="payment_visa" checked class="w-4 h-4">
                        </div>
                    </div>
                </div>

                <hr>

                <div>
                    <h3 class="text-lg mb-4">Thông tin thanh toán</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-2">Momo Partner Code</label>
                            <input type="text" name="momo_partner" placeholder="YOUR_PARTNER_CODE" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Momo Access Key</label>
                            <input type="password" name="momo_access" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">VNPay TMN Code</label>
                            <input type="text" name="vnpay_tmn" placeholder="YOUR_TMN_CODE" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">VNPay Hash Secret</label>
                            <input type="password" name="vnpay_secret" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">
                        Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>

        {{-- Shipping Settings --}}
        <div x-show="activeTab === 'shipping'" class="space-y-6" style="display: none;">
            <form action="{{ route('admin.settings.update-shipping') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
                @csrf
                <div>
                    <h3 class="text-lg mb-4">Cấu hình vận chuyển</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-2">Miễn phí vận chuyển từ (VNĐ)</label>
                            <input type="number" name="free_ship_from" value="500000" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Phí vận chuyển cố định (VNĐ)</label>
                            <input type="number" name="shipping_fee" value="30000" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm mb-4">Khu vực giao hàng</label>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 border rounded">
                                <span class="text-sm">Nội thành TP.HCM</span>
                                <input type="checkbox" name="ship_inner_hcm" checked class="w-4 h-4">
                            </div>
                            <div class="flex items-center justify-between p-3 border rounded">
                                <span class="text-sm">Ngoại thành TP.HCM</span>
                                <input type="checkbox" name="ship_outer_hcm" checked class="w-4 h-4">
                            </div>
                            <div class="flex items-center justify-between p-3 border rounded">
                                <span class="text-sm">Các tỉnh thành khác</span>
                                <input type="checkbox" name="ship_provinces" checked class="w-4 h-4">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">
                        Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>

        {{-- Notifications Settings --}}
        <div x-show="activeTab === 'notifications'" class="space-y-6" style="display: none;">
            <form action="{{ route('admin.settings.update-notifications') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
                @csrf
                <div>
                    <h3 class="text-lg mb-4">Thông báo email</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Email xác nhận đơn hàng</p>
                                <p class="text-xs text-gray-500">Gửi email khi đơn hàng được đặt thành công</p>
                            </div>
                            <input type="checkbox" name="email_order_confirm" checked class="w-4 h-4">
                        </div>
                        <hr>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Email cập nhật đơn hàng</p>
                                <p class="text-xs text-gray-500">Gửi email khi trạng thái đơn hàng thay đổi</p>
                            </div>
                            <input type="checkbox" name="email_order_update" checked class="w-4 h-4">
                        </div>
                        <hr>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Email giao hàng thành công</p>
                                <p class="text-xs text-gray-500">Gửi email khi đơn hàng được giao thành công</p>
                            </div>
                            <input type="checkbox" name="email_delivery_success" checked class="w-4 h-4">
                        </div>
                        <hr>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm">Email khuyến mãi</p>
                                <p class="text-xs text-gray-500">Gửi email thông báo về các chương trình khuyến mãi</p>
                            </div>
                            <input type="checkbox" name="email_promotions" class="w-4 h-4">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">
                        Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Alpine.js will handle tabs
</script>
@endpush
@endsection
