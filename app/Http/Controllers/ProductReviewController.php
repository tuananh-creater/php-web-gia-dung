<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReviewRequest;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;

class ProductReviewController extends Controller
{
    public function store(ProductReviewRequest $request, Product $product): RedirectResponse
    {
        ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ],
            [
                'rating' => $request->rating,
                'content' => $request->content,
                'is_visible' => true,
            ]
        );

        return back()->with('success', 'Đánh giá của bạn đã được lưu.');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        abort_if($review->user_id !== auth()->id(), 403);

        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá.');
    }
}