<footer class="site-footer">
    <div class="container">
        <div class="footer-top row g-4">
            <div class="col-lg-4">
                <div class="footer-brand">HomeKit</div>
                <p class="footer-text">
                    Thương hiệu mang đến những sản phẩm
                    trang trí và gia dụng tinh tế cho không gian sống hiện đại.
                </p>

                <ul class="footer-contact list-unstyled mb-0">
                    <li><i class="bi bi-geo-alt"></i>Hà Đông, Hà Nội</li>
                    <li><i class="bi bi-telephone"></i> 1900 6750</li>
                    <li><i class="bi bi-envelope"></i> support@gmail.com</li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h5 class="footer-title">Về chúng tôi</h5>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                    <li><a href="{{ route('collections') }}">Bộ sưu tập</a></li>
                    <li><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                    <li><a href="{{ route('faq') }}">Hỏi đáp</a></li>
                    <li><a href="{{ route('news.page') }}">Tin tức</a></li>
                    <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h5 class="footer-title">Đăng ký nhận tin</h5>
                <p class="footer-text">Nhận thông tin sản phẩm mới nhất và các chương trình khuyến mãi.</p>

                <form class="footer-subscribe">
                    <input type="email" class="form-control" placeholder="Nhập địa chỉ email">
                    <button type="button"><i class="bi bi-arrow-right"></i></button>
                </form>

                <div class="payment-box mt-4">
                    <span class="payment-chip">MOMO</span>
                    <span class="payment-chip">ZaloPay</span>
                    <span class="payment-chip">VNPAY</span>
                    <span class="payment-chip">VISA</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © Bản quyền thuộc về HomeKit
        </div>
    </div>
</footer>