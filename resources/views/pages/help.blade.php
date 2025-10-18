{{-- 
  Help Page (Contact & Policies)
  Converted from: /pages/HelpPage.tsx
--}}

@extends('layouts.app')

@section('title', 'Trợ Giúp & Chính Sách - VIVILLAN')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-16">
        {{-- Header --}}
        <div class="text-center mb-16">
            <h1 class="text-4xl mb-4" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                Trợ Giúp & Chính Sách
            </h1>
            <p class="text-sm opacity-60 max-w-2xl mx-auto">
                Tìm hiểu các chính sách và điều khoản của VIVILLAN để có trải nghiệm mua sắm tốt nhất
            </p>
        </div>

        {{-- Policies Grid --}}
        <div class="max-w-6xl mx-auto space-y-12">
            {{-- Policy 1: Payment --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        1
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Chính Sách Thanh Toán
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>VIVILLAN chấp nhận nhiều hình thức thanh toán khác nhau để mang lại sự tiện lợi tối đa cho khách hàng.</p>
                            <p>Thanh toán khi nhận hàng (COD): Áp dụng cho tất cả đơn hàng trong nước.</p>
                            <p>Chuyển khoản ngân hàng: Quý khách vui lòng chuyển khoản trước khi đơn hàng được xử lý.</p>
                            <p>Thẻ tín dụng/ghi nợ: Chấp nhận Visa, MasterCard, JCB.</p>
                            <p>Ví điện tử: Hỗ trợ Momo, ZaloPay, VNPay.</p>
                            <p>Mọi giao dịch đều được mã hóa và bảo mật tuyệt đối.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy 2: Shipping --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        2
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Chính Sách Vận Chuyển
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>Thời gian giao hàng: 2-5 ngày làm việc tại nội thành Hà Nội và TP.HCM.</p>
                            <p>Đơn hàng ngoại thành: 3-7 ngày làm việc.</p>
                            <p>Miễn phí vận chuyển cho đơn hàng từ 2.000.000đ trở lên.</p>
                            <p>Phí vận chuyển tiêu chuẩn: 30.000đ - 50.000đ tùy khu vực.</p>
                            <p>Giao hàng nhanh: Áp dụng phí đặc biệt, giao trong vòng 24h.</p>
                            <p>Quý khách vui lòng kiểm tra hàng trước khi thanh toán.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy 3: Inspection --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        3
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Chính Sách Kiểm Hàng
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>Khách hàng có quyền kiểm tra sản phẩm trước khi thanh toán.</p>
                            <p>Vui lòng kiểm tra kỹ: Màu sắc, size, chất liệu, và tình trạng sản phẩm.</p>
                            <p>Không chấp nhận đổi trả nếu sản phẩm đã qua sử dụng hoặc có dấu hiệu đã qua sử dụng.</p>
                            <p>Nếu phát hiện lỗi từ nhà sản xuất, vui lòng từ chối nhận hàng và liên hệ hotline ngay.</p>
                            <p>Nhân viên giao hàng sẽ hỗ trợ quý khách trong quá trình kiểm tra.</p>
                            <p>Thời gian kiểm tra tối đa: 5 phút.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy 4: Privacy --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        4
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Chính Sách Bảo Mật
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>VIVILLAN cam kết bảo vệ thông tin cá nhân của khách hàng.</p>
                            <p>Thông tin khách hàng chỉ được sử dụng cho mục đích xử lý đơn hàng và chăm sóc khách hàng.</p>
                            <p>Chúng tôi không chia sẻ thông tin với bên thứ ba trừ khi có yêu cầu pháp lý.</p>
                            <p>Dữ liệu được mã hóa và lưu trữ an toàn trên hệ thống.</p>
                            <p>Quý khách có quyền yêu cầu xóa hoặc cập nhật thông tin cá nhân bất kỳ lúc nào.</p>
                            <p>Liên hệ: <a href="mailto:privacy@vivillan.com" class="underline hover:opacity-100">privacy@vivillan.com</a> để biết thêm chi tiết.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy 5: Terms --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        5
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Điều Khoản Sử Dụng
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>Khi sử dụng website và dịch vụ của VIVILLAN, quý khách đồng ý với các điều khoản sau:</p>
                            <p>Quý khách phải đủ 18 tuổi hoặc có sự đồng ý của người giám hộ.</p>
                            <p>Thông tin đăng ký phải chính xác và đầy đủ.</p>
                            <p>Nghiêm cấm sử dụng website cho mục đích bất hợp pháp.</p>
                            <p>VIVILLAN có quyền thay đổi điều khoản mà không cần thông báo trước.</p>
                            <p>Mọi tranh chấp sẽ được giải quyết theo pháp luật Việt Nam.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy 6: Exchange --}}
            <div class="bg-gray-50 p-8 rounded-sm">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-xl">
                        6
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif;">
                            Chính Sách Đổi Hàng
                        </h2>
                        <div class="space-y-4 text-sm leading-relaxed opacity-80">
                            <p>Thời gian đổi hàng: Trong vòng 7 ngày kể từ ngày nhận hàng.</p>
                            <p>Điều kiện đổi hàng: Sản phẩm chưa qua sử dụng, còn nguyên tem mác, hóa đơn.</p>
                            <p>Sản phẩm lỗi từ nhà sản xuất: Đổi miễn phí trong vòng 30 ngày.</p>
                            <p>Đổi size/màu: Khách hàng chịu phí vận chuyển 2 chiều.</p>
                            <p>Không áp dụng đổi hàng cho sản phẩm sale trên 50%.</p>
                            <p>Để đổi hàng, vui lòng liên hệ hotline: <a href="tel:+84779177707" class="underline hover:opacity-100">+84 77 917 707</a>.</p>
                            <p>Sản phẩm đổi phải được đóng gói cẩn thận và gửi về địa chỉ cửa hàng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Section --}}
        <div class="mt-20 text-center border-t border-gray-200 pt-16">
            <h3 class="text-2xl mb-6" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                Cần Hỗ Trợ Thêm?
            </h3>
            <p class="text-sm opacity-60 mb-8 max-w-xl mx-auto">
                Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với đội ngũ chăm sóc khách hàng của chúng tôi
            </p>
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                {{-- Email --}}
                <a href="mailto:hello@vivillan.com" class="flex items-center gap-3 hover:opacity-70 transition-opacity">
                    <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs uppercase tracking-wider opacity-60">Email</p>
                        <p class="text-sm">hello@vivillan.com</p>
                    </div>
                </a>

                {{-- Phone --}}
                <a href="tel:+84779177707" class="flex items-center gap-3 hover:opacity-70 transition-opacity">
                    <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs uppercase tracking-wider opacity-60">Hotline</p>
                        <p class="text-sm">+84 77 917 707</p>
                    </div>
                </a>

                {{-- Address --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs uppercase tracking-wider opacity-60">Cửa hàng</p>
                        <p class="text-sm">387 Hoàng Quốc Việt, Cầu Giấy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
