import Alpine from 'alpinejs'

window.Alpine = Alpine

// 🧠 Hàm header() mô phỏng menu giống React
window.header = function() {
    return {
        currentPage: 'home',
        hoveredMenu: null,
        isSearchOpen: false,
        menuItems: [
            { 
                id: 'men', 
                label: 'NAM', 
                hasDropdown: true, 
                subItems: [
                    { id: 'view-all', label: 'Tất cả' },
                    { id: 't-shirt', label: 'Áo thun' },
                    { id: 'pants', label: 'Quần' },
                    { id: 'coats-jackets', label: 'Áo khoác' },
                ]
            },
            { 
                id: 'women', 
                label: 'NỮ', 
                hasDropdown: true, 
                subItems: [
                    { id: 'view-all', label: 'Tất cả' },
                    { id: 'dress', label: 'Đầm' },
                    { id: 'skirt', label: 'Chân váy' },
                    { id: 'top', label: 'Áo' },
                    { id: 'jacket', label: 'Áo khoác' },
                ]
            },
            { 
                id: 'accessories', 
                label: 'PHỤ KIỆN', 
                hasDropdown: true, 
                subItems: [
                    { id: 'view-all', label: 'Tất cả' },
                    { id: 'bag', label: 'Túi xách' },
                    { id: 'shoes', label: 'Giày' },
                ]
            },
            { id: 'explore', label: 'KHÁM PHÁ', hasDropdown: false },
            { id: 'contact', label: 'TRỢ GIÚP', hasDropdown: false },
        ],
        navigate(page, subPage = null) {
            console.log('Đi tới', page, subPage)
            this.currentPage = page
            // Có thể dùng window.location.href hoặc route() ở đây
        }
    }
}

Alpine.start()
