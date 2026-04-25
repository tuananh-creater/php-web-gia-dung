<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PageController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showUserLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan', [AccountController::class, 'index'])->name('account.index');
    Route::put('/tai-khoan', [AccountController::class, 'updateProfile'])->name('account.update');

    Route::get('/tai-khoan/don-hang', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/tai-khoan/don-hang/{order}', [AccountController::class, 'showOrder'])->name('account.orders.show');

    Route::post('/san-pham/{product}/danh-gia', [ProductReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/danh-gia/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::post('/tin-tuc/{post}/binh-luan', [PostCommentController::class, 'store'])->name('comments.store');
    Route::delete('/binh-luan/{comment}', [PostCommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/ve-chung-toi', [PageController::class, 'about'])->name('about');
Route::get('/hoi-dap', [PageController::class, 'faq'])->name('faq');
Route::get('/bo-suu-tap', [PageController::class, 'collections'])->name('collections');

Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.page');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/cap-nhat/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/gio-hang/xoa/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/gio-hang/xoa-tat-ca', [CartController::class, 'clear'])->name('cart.clear');

Route::post('/gio-hang/ap-ma-giam-gia', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::delete('/gio-hang/xoa-ma-giam-gia', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/thanh-toan', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/thanh-toan', [CartController::class, 'storeOrder'])->name('checkout.store');
Route::get('/dat-hang-thanh-cong/{order}', [CartController::class, 'success'])->name('checkout.success');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', AdminProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
        Route::resource('banners', BannerController::class);
        Route::resource('coupons', CouponController::class);
        Route::resource('posts', AdminPostController::class);
        Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');

        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/orders/excel', [ExportController::class, 'ordersExcel'])->name('orders.excel');
            Route::get('/orders/pdf', [ExportController::class, 'ordersPdf'])->name('orders.pdf');

            Route::get('/contacts/excel', [ExportController::class, 'contactsExcel'])->name('contacts.excel');
            Route::get('/contacts/pdf', [ExportController::class, 'contactsPdf'])->name('contacts.pdf');

            Route::get('/revenue/excel', [ExportController::class, 'revenueExcel'])->name('revenue.excel');
            Route::get('/revenue/pdf', [ExportController::class, 'revenuePdf'])->name('revenue.pdf');
        });
    });