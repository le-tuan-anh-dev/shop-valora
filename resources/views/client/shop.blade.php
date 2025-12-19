@extends('client.layouts.master')

@section('title', 'Velora - Online Fashion Store')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Cửa hàng</h4>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space pt-0">
        <div class="custom-container container">
            <div class="row">
                <!-- Sidebar bên trái giữ nguyên -->
                <div class="col-3">
                    <div class="custom-accordion theme-scrollbar left-box">
                        <div class="left-accordion">
                            <h5>Quay lại</h5>
                            <i class="back-button fa-solid fa-xmark"></i>
                        </div>
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <form method="GET" action="{{ route('shop.index') }}" class="search-box">
                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <input type="search" name="search" placeholder="Tìm kiếm..."
                                    value="{{ request('search') }}">
                                <button type="submit" style="border: none; background: none; cursor: pointer;">
                                    <i class="iconsax" data-icon="search-normal-2"></i>
                                </button>
                            </form>

                            <div class="accordion-item">
                                <h2 class="accordion-header tags-header">
                                    <button class="accordion-button">
                                        <span>Bộ lọc đã áp dụng</span>
                                        <span>xem tất cả</span>
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse show" id="panelsStayOpen-collapse">
                                    <div class="accordion-body">
                                        <ul class="tags">
                                            @if (request('category'))
                                                @php
                                                    $selectedCategory =
                                                        $categories->firstWhere('id', request('category')) ??
                                                        \App\Models\Admin\Category::find(request('category'));
                                                @endphp
                                                @if ($selectedCategory)
                                                    <li>
                                                        <a
                                                            href="{{ route('shop.index', array_filter(request()->except('category'))) }}">
                                                            {{ $selectedCategory->name }} <i class="iconsax"
                                                                data-icon="close-circle"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if (request('min_price') || request('max_price'))
                                                <li>
                                                    <a
                                                        href="{{ route('shop.index', array_filter(request()->except(['min_price', 'max_price']))) }}">
                                                        Giá:
                                                        {{ number_format(request('min_price', $minPrice), 0, ',', '.') }}₫ -
                                                        {{ number_format(request('max_price', $maxPrice), 0, ',', '.') }}₫
                                                        <i class="iconsax" data-icon="close-circle"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (request('attributes'))
                                                @php
                                                    $selectedAttrValues = array_filter(request('attributes', []));
                                                    $selectedValues = \App\Models\admin\AttributeValue::with(
                                                        'attribute',
                                                    )
                                                        ->whereIn('id', $selectedAttrValues)
                                                        ->get();
                                                @endphp
                                                @foreach ($selectedValues as $selectedValue)
                                                    <li>
                                                        @php
                                                            $newAttributes = array_values(
                                                                array_diff($selectedAttrValues, [$selectedValue->id]),
                                                            );
                                                            $params = request()->except(['attributes']);
                                                            if (!empty($newAttributes)) {
                                                                $params['attributes'] = $newAttributes;
                                                            }
                                                        @endphp
                                                        <a href="{{ route('shop.index', $params) }}">
                                                            {{ $selectedValue->attribute->name ?? '' }}:
                                                            {{ $selectedValue->value }}
                                                            <i class="iconsax" data-icon="close-circle"></i>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                            @if (
                                                !request('category') &&
                                                    !request('min_price') &&
                                                    !request('max_price') &&
                                                    empty(array_filter(request('attributes', []))))
                                                <li class="text-muted">Chưa có bộ lọc nào</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseTwo">
                                        <span>Danh mục</span>
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseTwo">
                                    <div class="accordion-body">
                                        <ul class="catagories-side theme-scrollbar">
                                            <li>
                                                <a href="{{ route('shop.index') }}"
                                                    class="d-block py-2 {{ !request('category') ? 'fw-bold' : '' }}">
                                                    Tất cả sản phẩm
                                                </a>
                                            </li>
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a href="{{ route('shop.index', ['category' => $category->id]) }}"
                                                        class="d-block py-2 {{ request('category') == $category->id ? 'fw-bold' : '' }}">
                                                        {{ $category->name }}
                                                        ({{ $category->products()->where('is_active', 1)->count() }})
                                                    </a>
                                                    @if ($category->children->count() > 0)
                                                        <ul class="ms-3 mt-1">
                                                            @foreach ($category->children as $child)
                                                                <li>
                                                                    <a href="{{ route('shop.index', ['category' => $child->id]) }}"
                                                                        class="d-block py-1 {{ request('category') == $child->id ? 'fw-bold' : '' }}">
                                                                        {{ $child->name }}
                                                                        ({{ $child->products()->where('is_active', 1)->count() }})
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseFour">
                                        <span>Lọc theo giá</span>
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseFour">
                                    <div class="accordion-body">
                                        <form method="GET" action="{{ route('shop.index') }}" id="price-filter-form">
                                            @if (request('category'))
                                                <input type="hidden" name="category" value="{{ request('category') }}">
                                            @endif
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span id="min-price-display"
                                                        class="fw-semibold">{{ number_format(request('min_price', $minPrice), 0, ',', '.') }}₫</span>
                                                    <span class="mx-2">-</span>
                                                    <span id="max-price-display"
                                                        class="fw-semibold">{{ number_format(request('max_price', $maxPrice), 0, ',', '.') }}₫</span>
                                                </div>
                                                <div class="range-slider">
                                                    <input type="range" class="range-slider-input" id="min-price-slider"
                                                        name="min_price" min="{{ $minPrice }}"
                                                        max="{{ $maxPrice }}" step="1000"
                                                        value="{{ request('min_price', $minPrice) }}"
                                                        oninput="updatePriceDisplay()">
                                                    <input type="range" class="range-slider-input" id="max-price-slider"
                                                        name="max_price" min="{{ $minPrice }}"
                                                        max="{{ $maxPrice }}" step="1000"
                                                        value="{{ request('max_price', $maxPrice) }}"
                                                        oninput="updatePriceDisplay()">
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mt-3">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">Áp
                                                    dụng</button>
                                                @if (request('min_price') || request('max_price'))
                                                    <a href="{{ route('shop.index', array_filter(request()->except(['min_price', 'max_price']))) }}"
                                                        class="btn btn-sm btn-secondary">Xóa</a>
                                                @endif
                                            </div>
                                        </form>
                                        <script>
                                            function updatePriceDisplay() {
                                                const minSlider = document.getElementById('min-price-slider');
                                                const maxSlider = document.getElementById('max-price-slider');
                                                const minDisplay = document.getElementById('min-price-display');
                                                const maxDisplay = document.getElementById('max-price-display');

                                                // Đảm bảo min không lớn hơn max
                                                if (parseInt(minSlider.value) > parseInt(maxSlider.value)) {
                                                    minSlider.value = maxSlider.value;
                                                }

                                                minDisplay.textContent = parseInt(minSlider.value).toLocaleString('vi-VN') + '₫';
                                                maxDisplay.textContent = parseInt(maxSlider.value).toLocaleString('vi-VN') + '₫';
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>

                            @foreach ($attributes as $index => $attribute)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseAttr{{ $attribute->id }}">
                                            <span>{{ $attribute->name }}</span>
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse show"
                                        id="panelsStayOpen-collapseAttr{{ $attribute->id }}">
                                        <div class="accordion-body">
                                            <form method="GET" action="{{ route('shop.index') }}"
                                                class="attribute-filter-form">
                                                @if (request('category'))
                                                    <input type="hidden" name="category"
                                                        value="{{ request('category') }}">
                                                @endif
                                                @if (request('search'))
                                                    <input type="hidden" name="search"
                                                        value="{{ request('search') }}">
                                                @endif
                                                @if (request('min_price'))
                                                    <input type="hidden" name="min_price"
                                                        value="{{ request('min_price') }}">
                                                @endif
                                                @if (request('max_price'))
                                                    <input type="hidden" name="max_price"
                                                        value="{{ request('max_price') }}">
                                                @endif
                                                @if (request('attributes'))
                                                    @foreach (request('attributes') as $attrValue)
                                                        @if ($attrValue != '')
                                                            <input type="hidden" name="attributes[]"
                                                                value="{{ $attrValue }}">
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($attribute->values as $value)
                                                        <li class="mb-2">
                                                            <label class="form-check d-flex align-items-center">
                                                                <input type="checkbox"
                                                                    class="form-check-input attribute-checkbox"
                                                                    name="attributes[]" value="{{ $value->id }}"
                                                                    {{ in_array($value->id, request('attributes', [])) ? 'checked' : '' }}
                                                                    onchange="this.form.submit()">
                                                                <span class="form-check-label ms-2">
                                                                    {{ $value->value }}
                                                                    <span
                                                                        class="text-muted">({{ $value->product_count }})</span>
                                                                </span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Phần nội dung bên phải với 3 sản phẩm -->
                <div class="col-xl-9">
                    <div class="sticky">
                        <div class="top-filter-menu">
                            <div>
                                <a class="filter-button btn">
                                    <h6><i class="iconsax" data-icon="filter"></i>Bộ lọc</h6>
                                </a>
                                <form method="GET" action="{{ route('shop.index') }}" class="category-dropdown">
                                    @if (request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    @if (request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    @if (request('min_price'))
                                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                    @endif
                                    @if (request('max_price'))
                                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                    @endif
                                    @if (request('attributes'))
                                        @foreach (request('attributes') as $attrValue)
                                            @if ($attrValue != '')
                                                <input type="hidden" name="attributes[]" value="{{ $attrValue }}">
                                            @endif
                                        @endforeach
                                    @endif
                                    <label for="sort">Sắp xếp theo:</label>
                                    <select class="form-select" id="sort" name="sort"
                                        onchange="this.form.submit()">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="best_selling"
                                            {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Bán chạy nhất
                                        </option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                            Giá: Thấp đến cao</option>
                                        <option value="price_high"
                                            {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá: Cao đến thấp
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="product-tab-content ratio1_3">
                            <div class="row-cols-lg-4 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4">
                                @forelse($products as $product)
                                    <div class="col">
                                        <div class="product-box">
                                            <div class="img-wrapper">
                                                {{-- Badge Giảm Giá --}}
                                                @if ($product->discount_price)
                                                    @php
                                                        $discountPercent = round(
                                                            (($product->discount_price - $product->base_price) /
                                                                $product->base_price) *
                                                                100,
                                                        );
                                                    @endphp
                                                    <div class="label-block">
                                                        <img src="{{ asset('client/assets/images/product/2.png') }}"
                                                            alt="label">
                                                        <span>Giảm <br>giá!</span>
                                                    </div>
                                                @endif

                                                {{-- Ảnh sản phẩm --}}
                                                <div class="product-image">
                                                    <a href="{{ route('products.detail', $product->id) }}">
                                                        <img class="bg-img"
                                                            src="{{ $product->image_main ? asset('storage/' . $product->image_main) : asset('client/assets/images/product/product-4/1.jpg') }}"
                                                            alt="{{ $product->name }}"
                                                            onerror="this.src='{{ asset('client/assets/images/product/product-4/1.jpg') }}'">
                                                    </a>
                                                </div>

                                                {{-- Icons --}}
                                                {{-- Icon chỉ còn wishlist --}}
                                                <style>
                                                    .cart-info-icon i {
                                                        font-size: 30px;
                                                        color: #777;
                                                        /* màu mặc định */
                                                        cursor: pointer;
                                                        transition: color 0.2s;
                                                    }

                                                    .cart-info-icon i:hover {
                                                        color: red;
                                                        /* di vào thì đỏ */
                                                    }

                                                    /* Toast thông báo */
                                                    .wishlist-toast {
                                                        position: fixed;
                                                        top: 50%;
                                                        left: 50%;
                                                        transform: translate(-50%, -50%);
                                                        /* căn giữa theo cả 2 trục */
                                                        background: rgba(0, 0, 0, 0.85);
                                                        color: #fff;
                                                        padding: 10px 16px;
                                                        border-radius: 6px;
                                                        font-size: 14px;
                                                        opacity: 0;
                                                        visibility: hidden;
                                                        transition: all 0.3s ease;
                                                        z-index: 9999;
                                                    }

                                                    .wishlist-toast.show {
                                                        opacity: 1;
                                                        visibility: visible;
                                                        transform: translateY(0);
                                                    }
                                                </style>

                                                <div class="cart-info-icon">
                                                    @auth
                                                        {{-- User đã đăng nhập: bấm là thêm vào wishlist --}}
                                                        <a href="#"
                                                            onclick="
                                                                        event.preventDefault();
                                                                        showWishlistToast('Đã thêm vào danh sách yêu thích');
                                                                        document.getElementById('wishlist-form-{{ $product->id }}').submit();
                                                                ">
                                                            <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                data-bs-title="Thêm vào yêu thích"></i>
                                                        </a>

                                                        <form id="wishlist-form-{{ $product->id }}"
                                                            action="{{ route('wishlist.add', $product->id) }}" method="POST"
                                                            class="d-none">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        {{-- Chưa đăng nhập: bấm chuyển qua trang login --}}
                                                        <a href="{{ route('login') }}">
                                                            <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                data-bs-title="Đăng nhập để thêm vào yêu thích"></i>
                                                        </a>
                                                    @endauth
                                                </div>

                                                {{-- Toast hiển thị thông báo --}}
                                                <div id="wishlist-toast" class="wishlist-toast"></div>

                                                <script>
                                                    function showWishlistToast(message) {
                                                        const toast = document.getElementById('wishlist-toast');
                                                        if (!toast) return;

                                                        toast.textContent = message;
                                                        toast.classList.add('show');

                                                        // 1.5 giây sau thì ẩn đi
                                                        setTimeout(() => {
                                                            toast.classList.remove('show');
                                                        }, 1500);
                                                    }
                                                </script>
                                            </div>

                                            {{-- Chi tiết sản phẩm --}}
                                            <div class="product-detail">
                                                <div class="add-button">
                                                    <a href="{{ route('products.detail', $product->id) }}"
                                                        title="Xem chi tiết" tabindex="0">
                                                        <i class="fa-solid fa-eye"></i> Xem chi tiết
                                                    </a>
                                                </div>

                                                <a href="{{ route('products.detail', $product->id) }}">
                                                    <h5>{{ Str::limit($product->name, 40) }}</h5>
                                                </a>

                                                {{-- Giá sản phẩm --}}
                                                <p>
                                                    @if ($product->discount_price)
                                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                        <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                        @php
                                                            $discountPercent = round(
                                                                (($product->discount_price - $product->base_price) /
                                                                    $product->base_price) *
                                                                    100,
                                                            );
                                                        @endphp
                                                        <span>{{ $discountPercent }}%</span>
                                                    @else
                                                        {{ number_format($product->base_price, 0, ',', '.') }}₫
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
                                            <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        @if ($products->hasPages())
                            <div class="pagination-wrap">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
