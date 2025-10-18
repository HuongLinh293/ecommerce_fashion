<div x-cloak x-data>
    <!-- Small toast that appears above the cart icon. Reads from Alpine.store('cartToast') with a JS fallback -->
    <div
        x-show="(typeof Alpine !== 'undefined' && Alpine.store('cartToast') && Alpine.store('cartToast').show) || (window._cartToastStub && window._cartToastStub.show)"
        x-transition
        class="fixed z-50 pointer-events-none"
        style="top: 0.9rem; right: 3.5rem;"
    >
        <div class="pointer-events-auto bg-black text-white text-sm px-3 py-2 rounded shadow-lg">
            <span x-text="(typeof Alpine !== 'undefined' && Alpine.store('cartToast') && Alpine.store('cartToast').message) || (window._cartToastStub && window._cartToastStub.message)"></span>
        </div>
    </div>
</div>
