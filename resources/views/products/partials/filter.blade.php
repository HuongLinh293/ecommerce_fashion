<div class="space-y-8" x-data="{ _timer: null, onInput() { if(this._timer) clearTimeout(this._timer); this._timer = setTimeout(()=>{ this.$refs.form.submit(); }, 250); }, submitNow(){ this.$refs.form.submit(); }, syncRange(e){ if(this.$refs?.max_input){ this.$refs.max_input.value = e.target.value; } this.onInput(); } }">

    <form method="GET" action="{{ route('products.index') }}" x-ref="form">
        {{-- Preserve current category in the form so route resolution remains consistent --}}
        <input type="hidden" name="category" value="{{ request('category', $category ?? '') }}">

    {{-- 🟩 KHOẢNG GIÁ --}}
    <div>
        <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Khoảng giá</h4>
        <div class="flex items-center gap-2 mb-4">
            <input type="number" name="min_price" id="min_price" placeholder="0"
                value="{{ request('min_price') }}"
                @input="onInput()"
                class="w-1/2 border border-gray-300 p-2 text-xs">
            <input x-ref="max_input" type="number" name="max_price" id="max_price" placeholder="6000000"
                value="{{ request('max_price') }}"
                @input="onInput()"
                class="w-1/2 border border-gray-300 p-2 text-xs">
        </div>

        <input type="range" min="0" max="6000000" step="50000" value="{{ request('max_price', 6000000) }}" @input="syncRange($event)" class="w-full">
        <div class="flex justify-between text-xs mt-1">
            <span>0 ₫</span>
            <span>6.000.000 ₫</span>
        </div>
    </div>

    {{-- 🟦 LOẠI SẢN PHẨM --}}
    @if(isset($types) && is_countable($types) && count($types))
        <div>
            <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Loại sản phẩm</h4>
            <ul class="space-y-1 text-sm">
                @foreach($types as $t)
                    <li>
                        <label class="flex items-center gap-2">
                            @php
                                // Compare type values case-insensitively to handle DB/case variance
                                $requestedTypes = array_map('mb_strtolower', (array) request('types', []));
                                $isChecked = in_array(mb_strtolower((string) $t), $requestedTypes, true);
                            @endphp
                            <input type="checkbox" name="types[]" value="{{ $t }}" @change="onInput()" {{ $isChecked ? 'checked' : '' }}>
                            {{ $t }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🟨 MÀU SẮC --}}
    @if(isset($colors) && is_countable($colors) && count($colors))
        <div>
            <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Màu sắc</h4>
            <ul class="space-y-1 text-sm">
                @foreach($colors as $color)
                    <li>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="colors[]" value="{{ $color }}" @change="onInput()" {{ in_array($color, (array) request('colors', [])) ? 'checked' : '' }}>
                            {{ $color }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div>
            <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Màu sắc</h4>
            <div class="text-xs text-gray-400">Không có dữ liệu màu sắc</div>
        </div>
    @endif

    {{-- 🟧 KÍCH CỠ --}}
    @if(isset($sizes) && is_countable($sizes) && count($sizes))
        <div>
            <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Kích cỡ</h4>
            <ul class="space-y-1 text-sm">
                @foreach($sizes as $size)
                    <li>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" @change="onInput()" {{ in_array($size, (array) request('sizes', [])) ? 'checked' : '' }}>
                            {{ $size }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div>
            <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Kích cỡ</h4>
            <div class="text-xs text-gray-400">Không có dữ liệu kích cỡ</div>
        </div>
    @endif


    {{-- Nút Áp dụng và Clear Filters ở dưới cùng --}}
    <div class="mt-8 space-y-2 flex gap-2">
        <a href="{{ route('products.index', ['category' => request('category', $category ?? null)]) }}" class="flex-1 block text-center border border-gray-300 px-4 py-2 text-xs uppercase tracking-[0.2em]">CLEAR FILTERS</a>
        <button type="button" @click="submitNow()" class="flex-1 bg-black text-white px-4 py-2 text-xs uppercase tracking-[0.2em]">APPLY</button>
    </div>

    </form>
</div>
