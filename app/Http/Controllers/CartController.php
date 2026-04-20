<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AdminNewOrderMail;
use App\Mail\OrderConfirmedMail;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $summary = $this->getCartSummary();

        return view('cart.index', compact('cart', 'summary'));
    }

    public function add(Request $request, Product $product)
    {
        if (!$product->status) {
            return back()->with('error', 'Sản phẩm hiện không khả dụng.');
        }

        if ($product->quantity <= 0) {
            return back()->with('error', 'Sản phẩm đã hết hàng.');
        }

        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1']
        ]);

        $qtyToAdd = (int) ($request->quantity ?? 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['quantity'] + $qtyToAdd;

            if ($newQty > $product->quantity) {
                $newQty = $product->quantity;
            }

            $cart[$product->id]['quantity'] = $newQty;
        } else {
            $qtyToAdd = min($qtyToAdd, $product->quantity);

            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) ($product->sale_price ?? $product->price),
                'original_price' => (float) $product->price,
                'image' => $product->image,
                'stock' => (int) $product->quantity,
                'quantity' => $qtyToAdd,
            ];
        }

        session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ], [
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
        }

        $qty = (int) $request->quantity;

        if ($qty > $product->quantity) {
            $qty = $product->quantity;
        }

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['quantity'] = $qty;
            $cart[$product->id]['stock'] = (int) $product->quantity;
            $cart[$product->id]['price'] = (float) ($product->sale_price ?? $product->price);
            $cart[$product->id]['original_price'] = (float) $product->price;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => ['required', 'string']
        ], [
            'coupon_code.required' => 'Vui lòng nhập mã giảm giá.'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng đang trống.');
        }

        $subtotal = $this->cartSubtotal();
        $code = strtoupper(trim($request->coupon_code));

        $coupon = Coupon::query()
            ->where('code', $code)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhereDate('expired_at', '>=', now()->toDateString());
            })
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        if ($subtotal < (float) $coupon->min_order_value) {
            return back()->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã này.');
        }

        $discountAmount = 0;

        if ($coupon->discount_type === 'percent') {
            $discountAmount = $subtotal * ((float) $coupon->discount_value / 100);
        } else {
            $discountAmount = (float) $coupon->discount_value;
        }

        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'title' => $coupon->title,
            'discount_type' => $coupon->discount_type,
            'discount_value' => (float) $coupon->discount_value,
            'discount_amount' => $discountAmount,
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->with('success', 'Đã xóa mã giảm giá.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $summary = $this->getCartSummary();
        $user = auth()->user();

        return view('checkout.index', compact('cart', 'summary', 'user'));
    }

    public function storeOrder(CheckoutRequest $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $summary = $this->getCartSummary();

        DB::beginTransaction();

        try {
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if (!$product || !$product->status) {
                    throw new \Exception('Có sản phẩm không còn khả dụng.');
                }

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception('Sản phẩm "' . $product->name . '" không đủ số lượng tồn kho.');
                }
            }

            $data = $request->validated();

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'subtotal' => $summary['subtotal'],
                'discount_amount' => $summary['discount_amount'],
                'shipping_fee' => $summary['shipping_fee'],
                'total_amount' => $summary['total'],
                'coupon_code' => $summary['coupon_code'],
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'note' => $data['note'] ?? null,
            ]);

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                $unitPrice = (float) ($product->sale_price ?? $product->price);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $unitPrice * $item['quantity'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('coupon');

            $order->load('items');

            try {
                if (!empty($order->email)) {
                    Mail::to($order->email)->send(new OrderConfirmedMail($order));
                }

                $adminEmail = config('mail.admin.address');
                if (!empty($adminEmail)) {
                    Mail::to($adminEmail)->send(new AdminNewOrderMail($order));
                }
            } catch (\Throwable $mailException) {
                report($mailException);
            }

            return redirect()
                ->route('checkout.success', $order)
                ->with('success', 'Đặt hàng thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        $order->load('items');

        return view('checkout.success', compact('order'));
    }

    private function cartSubtotal(): float
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return $subtotal;
    }

    private function getCartSummary(): array
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon');

        $subtotal = 0;
        $itemCount = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }

        $discountAmount = 0;
        $couponCode = null;

        if (!empty($coupon)) {
            $discountAmount = (float) ($coupon['discount_amount'] ?? 0);
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
            $couponCode = $coupon['code'] ?? null;
        }

        $shippingFee = $subtotal > 0 ? 30000 : 0;
        if ($subtotal >= 1000000) {
            $shippingFee = 0;
        }

        $total = $subtotal - $discountAmount + $shippingFee;

        return [
            'item_count' => $itemCount,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'coupon' => $coupon,
            'coupon_code' => $couponCode,
        ];
    }
}