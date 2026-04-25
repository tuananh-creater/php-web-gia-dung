@extends('layouts.app')

@section('title', 'Hỏi đáp')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Hỏi đáp thường gặp</h2>
            <p>Những câu hỏi phổ biến về đặt hàng, giao hàng, đổi trả và sản phẩm thủ công.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="content-box h-100">
                    <h4 class="mb-3">Bạn cần hỗ trợ thêm?</h4>
                    <p class="text-muted">
                        Nếu chưa tìm thấy câu trả lời phù hợp, bạn có thể gửi câu hỏi trực tiếp cho chúng tôi qua trang liên hệ.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('contact') }}" class="btn btn-theme">Gửi liên hệ</a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Xem sản phẩm</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="content-box">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item faq-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}"
                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection