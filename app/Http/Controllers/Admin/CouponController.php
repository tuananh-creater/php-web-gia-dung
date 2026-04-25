<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()
            ->when(request()->filled('keyword'), function ($query) {
                $keyword = trim(request('keyword'));

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('code', 'like', '%' . $keyword . '%')
                        ->orWhere('title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(request()->filled('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request()->filled('discount_type'), function ($query) {
                $query->where('discount_type', request('discount_type'));
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));

        Coupon::create($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Thêm mã giảm giá thành công.');
    }

    public function show(Coupon $coupon)
    {
        return redirect()->route('admin.coupons.edit', $coupon);
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));

        $coupon->update($data);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Cập nhật mã giảm giá thành công.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Xóa mã giảm giá thành công.');
    }
}