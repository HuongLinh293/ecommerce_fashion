<footer class="bg-white text-black border-t border-black/10">
    <div class="container mx-auto px-6 lg:px-8 py-20">

        {{-- 📨 Newsletter Section --}}
        <div class="text-center mb-20 pb-16 border-b border-black/10">
            <div class="max-w-2xl mx-auto">
                <div class="w-2 h-2 bg-black mx-auto mb-8"></div>
                <h3 class="text-2xl uppercase tracking-[0.2em] mb-4 font-playfair">
                    JOIN THE MOVEMENT
                </h3>
                <p class="text-sm opacity-60 mb-10 tracking-wide">
                    Subscribe to receive updates on new collections, exclusive releases, and avant-garde insights.
                </p>
                <div class="flex max-w-lg mx-auto gap-0">
                    <input
                        type="email"
                        placeholder="YOUR EMAIL"
                        class="flex-1 bg-transparent border border-black/20 px-6 py-4 text-sm focus:outline-none focus:border-black placeholder:text-black/30 uppercase tracking-[0.15em]"
                    />
                    <button class="bg-black text-white px-10 py-4 text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors">
                        SUBSCRIBE
                    </button>
                </div>
            </div>
        </div>

        {{-- 🔗 Footer Links Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-16">

            {{-- Contact --}}
            <div>
                <h4 class="text-sm uppercase tracking-[0.2em] mb-6 opacity-40">CONTACT</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center gap-3 opacity-70 hover:opacity-100 transition-opacity cursor-pointer">
                        <x-lucide-mail class="w-4 h-4"/>
                        <p class="tracking-wide">hello@vivillan.com</p>
                    </div>
                    <div class="flex items-center gap-3 opacity-70 hover:opacity-100 transition-opacity cursor-pointer">
                        <x-lucide-phone class="w-4 h-4"/>
                        <p class="tracking-wide">+84 77 917 707</p>
                    </div>
                </div>
            </div>

            {{-- About --}}
            <div>
                <h4 class="text-sm uppercase tracking-[0.2em] mb-6 opacity-40">VIVILLAN</h4>
                <div class="space-y-4 text-sm">
                    <a href="{{ route('explore') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Philosophy</a>
                    <a href="/contact" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">About Us</a>
                    <a href="{{ route('explore') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Collections</a>
                    <a href="/contact" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Careers</a>
                </div>
            </div>

            {{-- Shop --}}
            <div>
                <h4 class="text-sm uppercase tracking-[0.2em] mb-6 opacity-40">SHOP</h4>
                <div class="space-y-4 text-sm">
                    <a href="{{ route('products.category', 'women') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Women</a>
<a href="{{ route('products.category', 'men') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Men</a>
<a href="{{ route('products.category', 'accessories') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Accessories</a>

                    <a href="{{ route('explore') }}" class="block opacity-70 hover:opacity-100 transition-opacity tracking-wide">Avant-Garde</a>
                </div>
            </div>

            {{-- Follow --}}
            <div>
                <h4 class="text-sm uppercase tracking-[0.2em] mb-6 opacity-40">FOLLOW</h4>
                <div class="flex items-center gap-5 mb-8">
                    <x-lucide-facebook class="w-5 h-5 opacity-60 cursor-pointer hover:opacity-100 transition-opacity" />
                    <x-lucide-instagram class="w-5 h-5 opacity-60 cursor-pointer hover:opacity-100 transition-opacity" />
                    <x-lucide-youtube class="w-5 h-5 opacity-60 cursor-pointer hover:opacity-100 transition-opacity" />
                    <x-lucide-mail class="w-5 h-5 opacity-60 cursor-pointer hover:opacity-100 transition-opacity" />
                    {{-- TikTok custom icon --}}
                    <svg class="w-5 h-5 opacity-60 cursor-pointer hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                    </svg>
                </div>

                <div class="mt-8">
                    <a 
                        href="http://online.gov.vn/Home/WebDetails/110870" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-block opacity-40 hover:opacity-70 transition-opacity"
                    >
                        <img 
                            src="https://images.dmca.com/Badges/dmca_protected_sml_120n.png?ID=5f8e24e0-8ec1-4bf1-a06c-ce37e3f0f1d0" 
                            alt="DMCA Protected"
                            class="h-10"
                        />
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-black/10 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs opacity-30 tracking-wider uppercase">
                © 2025 VIVILLAN — ALL RIGHTS RESERVED
            </p>
            <div class="flex gap-8 text-xs opacity-30 uppercase tracking-wider">
                <a href="/contact" class="hover:opacity-60 transition-opacity">Privacy</a>
                <a href="/contact" class="hover:opacity-60 transition-opacity">Terms</a>
                <a href="/contact" class="hover:opacity-60 transition-opacity">Shipping</a>
            </div>
        </div>
    </div>
</footer>
