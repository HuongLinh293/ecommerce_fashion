@props(['product'])

<div 
    x-data="{ quantity: 1, added: false, message: '' }" 
    class="w-full"
>
    <form 
        @submit.prevent="
            added = true;
            message = '{{ $product->name }} ×' + quantity;

            let formData = new FormData($event.target);
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') {
                    message = 'Lỗi khi thêm giỏ hàng!';
                }
            })
            .catch(() => {
                message = 'Không thể kết nối máy chủ!';
            })
            .finally(() => {
                setTimeout(() => added = false, 2500);
            });
        "
        id="addToCartForm"
        class="w-full"
    >
        @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="name" value="{{ $product->name }}">
    <input type="hidden" name="price" value="{{ $product->price }}">
    <input type="hidden" name="quantity" x-model="quantity">
    <input type="hidden" name="size" value="{{ $product->parsed_sizes[0] ?? '' }}">
    <input type="hidden" name="color" value="{{ $product->parsed_colors[0] ?? '' }}">
    <input type="hidden" name="image" value="{{ $product->image_url }}">

        <button 
            type="submit" 
            class="w-full bg-black text-white py-4 uppercase tracking-[0.2em] text-sm hover:bg-gray-800 transition-all flex items-center justify-center gap-3"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Add to Cart
        </button>
    </form>

    {{-- 🟢 Popup thông báo nâng cấp hiệu ứng --}}
    <div 
        x-show="added"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-[-15px]"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-[-15px]"
        class="fixed top-6 right-6 bg-black text-white px-6 py-4 rounded-xl shadow-xl flex items-start gap-3 z-50 w-[320px]"
        style="display: none;"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mt-0.5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        <div class="flex-1">
            <p class="font-semibold text-sm">Đã thêm vào giỏ hàng!</p>
            <p class="text-xs opacity-80" x-text="message"></p>
        </div>

        <a href="{{ route('cart.index') }}" class="bg-white text-black text-xs px-3 py-1 rounded hover:bg-gray-200 transition">
            Xem giỏ hàng
        </a>
    </div>
</div>
