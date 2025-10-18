@props(['product', 'inline' => false])

@php
    $isWishlisted = false;
    if (isset($wishlistedIds) && is_array($wishlistedIds)) {
        $isWishlisted = in_array($product->id, $wishlistedIds);
    }
    $inline = $inline ?? false;
@endphp

@php
    // classes vary depending on inline mode
    $btnBase = $inline
        ? 'inline-flex items-center justify-center w-10 h-10 bg-white rounded-md border border-gray-200 p-0 shadow-sm text-gray-400 hover:text-accent transition-colors'
        : 'absolute top-4 right-4 z-20 bg-white rounded-full p-2 shadow-md text-gray-400 hover:text-accent transition-colors';
    if ($isWishlisted) $btnBase .= ' text-accent';
@endphp

<button
    x-data
    data-product-name="{{ e($product->name) }}"
    @click.stop.prevent="(e) => {
        const id = {{ $product->id }};
        const btn = e.currentTarget;
        fetch('{{ route('wishlist.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: id })
        }).then(r => r.json()).then(data => {
            if (data.action === 'added') {
                btn.classList.add('text-accent');
                window.dispatchEvent(new CustomEvent('wishlist-toggled', { detail: { action: 'added', name: btn.dataset.productName } }));
            } else if (data.action === 'removed') {
                btn.classList.remove('text-accent');
                window.dispatchEvent(new CustomEvent('wishlist-toggled', { detail: { action: 'removed', name: btn.dataset.productName } }));
            }
        }).catch(err => console.error(err));
    }"
    class="{{ $btnBase }}"
    title="Thêm vào wishlist"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.656 3.172 11.828a4 4 0 010-5.656z" />
    </svg>
</button>
