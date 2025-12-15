@extends('client.layouts.master')

@section('title', 'Liên hệ')

@section('content')

{{-- Banner --}}
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Liên hệ</h4>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Thông tin liên hệ --}}
<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="contact-main">
            <div class="row gy-4 text-center">

                <div class="col-12">
                    <div class="title-1 address-content">
                        <p class="pb-0">Kết nối với chúng tôi<span></span></p>
                        <small class="text-muted">
                            Chúng tôi luôn sẵn sàng hỗ trợ và tư vấn cho bạn
                        </small>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="address-items h-100">
                        <div class="icon-box">
                            <i class="iconsax" data-icon="phone-calling"></i>
                        </div>
                        <div class="contact-box">
                            <h6>Hotline</h6>
                            <p>+84 912 345 678</p>
                            <small class="text-muted">Hỗ trợ từ 08:00 – 22:00</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="address-items h-100">
                        <div class="icon-box">
                            <i class="iconsax" data-icon="mail"></i>
                        </div>
                        <div class="contact-box">
                            <h6>Email</h6>
                            <p>valorashop@gmail.com</p>
                            <small class="text-muted">Phản hồi trong vòng 24 giờ</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="address-items h-100">
                        <div class="icon-box">
                            <i class="iconsax" data-icon="location"></i>
                        </div>
                        <div class="contact-box">
                            <h6>Địa chỉ</h6>
                            <p>123 Ba Đình</p>
                            <small class="text-muted">TP. Hà Nội</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="address-items h-100">
                        <div class="icon-box">
                            <i class="iconsax" data-icon="map-1"></i>
                        </div>
                        <div class="contact-box">
                            <h6>Giờ làm việc</h6>
                            <p>Thứ 2 – Chủ nhật</p>
                            <small class="text-muted">08:00 – 22:00</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Tư vấn & hỗ trợ --}}
<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="contact-main">
            <div class="row justify-content-center">

                <div class="col-lg-8">
                    <div class="contact-box text-center">
                        <h4>Tư vấn & hỗ trợ khách hàng</h4>
                        <p class="mt-2">
                            Nếu bạn cần tư vấn sản phẩm, hỗ trợ đặt hàng hoặc có bất kỳ thắc mắc nào,
                            vui lòng liên hệ với chúng tôi qua các kênh bên dưới.
                        </p>

                        <ul class="list-unstyled mt-4">
                            <li class="mb-3 d-flex justify-content-center align-items-center">
                                <i class="iconsax me-3" data-icon="phone-calling"></i>
                                <strong>Hotline:</strong>&nbsp; +84 912 345 678
                            </li>
                            <li class="mb-3 d-flex justify-content-center align-items-center">
                                <i class="iconsax me-3" data-icon="mail"></i>
                                <strong>Email:</strong>&nbsp; valorashop@gmail.com
                            </li>
                            <li class="mb-3 d-flex justify-content-center align-items-center">
                                <i class="iconsax me-3" data-icon="messages"></i>
                                <strong>Zalo:</strong>&nbsp; 0912 345 678
                            </li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
