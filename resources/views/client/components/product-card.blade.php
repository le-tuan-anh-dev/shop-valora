<div class="col-xxl-3 col-md-4 col-6">
    <div class="product-box">
        <div class="img-wrapper">
            <div class="product-image">
                <a href="{{ route('products.detail', $p->id) }}">
                    <img class="bg-img"
                         src="{{ $p->image_main ? asset('storage/' . $p->image_main) : asset('client/assets/images/no-image.png') }}"
                         alt="{{ $p->name }}">
                </a>
            </div>
        </div>
        <div class="product-detail">
            <a href="{{ route('products.detail', $p->id) }}">
                <h6>{{ $p->name }}</h6>
            </a>

            <p>
                @if ($p->discount_price)
                    ${{ number_format($p->discount_price) }}
                    <del>${{ number_format($p->base_price) }}</del>
                @else
                    ${{ number_format($p->base_price) }}
                @endif
            </p>
        </div>
    </div>
</div>
