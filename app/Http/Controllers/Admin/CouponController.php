<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(
        protected Coupon $coupon
    ) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = $request->per_page ?: PAGE_SIZE_DEFAULT;

        $coupons = $this->coupon
            ->newQuery()
            ->paginate($page_size);

        return $this->sendSuccess($coupons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $data = $request->only([
            'coupon',
            'description',
            'status',
            'type',
            'discount_percent',
            'discount_price',
            'quantity',
            'start_date',
            'end_date'
        ]);

        $coupon = $this->coupon
                        ->newQuery()
                        ->create($data);

        return $this->createSuccess($coupon);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return $this->sendSuccess($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->only([
            'coupon',
            'description',
            'status',
            'type',
            'discount_percent',
            'discount_price',
            'quantity',
            'start_date',
            'end_date'
        ]);

        $coupon->update($data);

        return $this->updateSuccess($coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return $this->deleteSuccess();
    }

    public function toggleStatus(Coupon $coupon)
    {
        $status = $coupon->status == ENABLE ? DISABLE : ENABLE;

        $coupon->update(['status' => $status]);

        return $this->updateSuccess($coupon);
    }
}
