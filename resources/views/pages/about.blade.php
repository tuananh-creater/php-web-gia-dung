@extends('layouts.app')

@section('title', 'Về chúng tôi')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="about-hero-box">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="section-kicker">HomeKit</span>
                    <h1 class="page-main-title">Không gian sống tinh tế từ cảm hứng thủ công</h1>
                    <p class="page-main-desc">
                        HomeKit là nơi hội tụ những sản phẩm decor và gia dụng mang vẻ đẹp mộc mạc,
                        ấm áp và gần gũi thiên nhiên. Chúng tôi tin rằng mỗi món đồ trong nhà không chỉ
                        để sử dụng, mà còn góp phần kể nên câu chuyện sống của riêng bạn.
                    </p>

                    <div class="d-flex gap-2 flex-wrap mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-theme">Xem sản phẩm</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-dark">Liên hệ tư vấn</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-hero-image-box">
                        <img src="{{ asset('storage/banners/banner-1.jpg') }}"
                             alt="Về HomeKit"
                             class="img-fluid rounded-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-space pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-box h-100">
                    <h3 class="mb-3">Câu chuyện thương hiệu</h3>
                    <p>
                        HomeKit được xây dựng với mong muốn đưa vẻ đẹp của chất liệu tự nhiên vào không gian sống hiện đại.
                        Chúng tôi lựa chọn những thiết kế thủ công có tính ứng dụng cao, dễ phối hợp với nhiều phong cách nội thất,
                        từ tối giản, vintage đến boho.
                    </p>
                    <p>
                        Từ giỏ, khay, đèn mây tre, gương decor đến các phụ kiện nhỏ cho góc nhà,
                        mỗi sản phẩm đều hướng đến sự cân bằng giữa công năng sử dụng và giá trị thẩm mỹ.
                    </p>
                    <p class="mb-0">
                        Không chỉ bán sản phẩm, chúng tôi còn muốn đồng hành cùng khách hàng trong hành trình
                        tạo dựng một không gian sống nhẹ nhàng, tinh tế và có chiều sâu cảm xúc.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-box h-100">
                    <h3 class="mb-3">Con số nổi bật</h3>

                    <div class="about-stat-list">
                        <div class="about-stat-item">
                            <div class="about-stat-number">{{ $stats['categories'] }}</div>
                            <div class="about-stat-label">Danh mục đang hoạt động</div>
                        </div>

                        <div class="about-stat-item">
                            <div class="about-stat-number">{{ $stats['products'] }}</div>
                            <div class="about-stat-label">Sản phẩm trên website</div>
                        </div>

                        <div class="about-stat-item">
                            <div class="about-stat-number">{{ $stats['posts'] }}</div>
                            <div class="about-stat-label">Bài viết chia sẻ</div>
                        </div>

                        <div class="about-stat-item">
                            <div class="about-stat-number">{{ $stats['orders'] }}</div>
                            <div class="about-stat-label">Đơn hàng đã phục vụ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-space pt-0">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Giá trị cốt lõi</span>
            <h2>Những điều HomeKit theo đuổi</h2>
        </div>

        <div class="row g-4">
            @foreach($values as $value)
                <div class="col-md-6 col-lg-4">
                    <div class="about-value-card">
                        <div class="about-value-icon">
                            <i class="bi {{ $value['icon'] }}"></i>
                        </div>
                        <h4>{{ $value['title'] }}</h4>
                        <p>{{ $value['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space pt-0">
    <div class="container">
        <div class="content-box">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h3 class="mb-3">Chúng tôi hỗ trợ bạn như thế nào?</h3>
                    <p class="text-muted">
                        Từ khâu chọn sản phẩm đến khi sản phẩm hiện diện trong không gian sống của bạn,
                        HomeKit luôn cố gắng mang lại trải nghiệm nhẹ nhàng, rõ ràng và tận tâm.
                    </p>

                    <ul class="about-service-list">
                        @foreach($services as $service)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $service }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-6">
                    <div class="about-service-image-box">
                        <img src="{{ asset('storage/posts/post-3.jpg') }}"
                             alt="Dịch vụ HomeKit"
                             class="img-fluid rounded-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection