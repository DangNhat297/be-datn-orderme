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
    )
    {
    }


    /**
     * @OA\Get(
     *      path="/admin/coupon",
     *      operationId="getCoupon",
     *      tags={"Coupon"},
     *      summary="Get list of Coupon",
     *      description="Returns list of Coupon",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="name coupon",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="type",
     *          description="type = 1 là giá chiết khấu %, type = 2 là giá đơn hàng cố định",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="limit page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="orderBy",
     *          description=" sort by query vd :-id,+id,+name,-name,-price,+price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $page_size = $request->per_page ?: PAGE_SIZE_DEFAULT;

        $coupons = $this->coupon
            ->newQuery()
            ->findByCoupon($request)
            ->findByType($request)
            ->findOrderBy($request)
            ->paginate($page_size);

        return $this->sendSuccess($coupons);
    }

    /**
     * @OA\Post(
     *      path="/admin/coupon",
     *      operationId="createCoupon",
     *      tags={"Coupon"},
     *      summary="Create new coupon",
     *      description="Returns coupon data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CouponCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       ),
     * )
     */
    public function store(CouponRequest $request)
    {
        $data = $request->only([
            'coupon',
            'description',
            'status',
            'type',
            'discount_percent',
            'price_sale_max',
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
     * @OA\Get(
     *      path="/admin/coupon/{id}",
     *      operationId="getCouponById",
     *      tags={"Coupon"},
     *      summary="Get coupon information",
     *      description="Returns coupon data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       ),
     * )
     */
    public function show(int $id)
    {
        $item = $this->coupon
            ->newQuery()
            ->findOrFail($id);
        return $this->sendSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/admin/coupon/{id}",
     *      operationId="deleteCoupon",
     *      tags={"Coupon"},
     *      summary="Delete existing coupon",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     * )
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

    /**
     * @OA\Put(
     *      path="/admin/coupon/{id}",
     *      operationId="updateCoupon",
     *      tags={"Coupon"},
     *      summary="Update existing coupon",
     *      description="Returns updated coupon data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CouponUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       )
     * )
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $data = $request->only([
            'coupon',
            'description',
            'status',
            'type',
            'discount_percent',
            'price_sale_max',
            'discount_price',
            'quantity',
            'start_date',
            'end_date'
        ]);

        $coupon->update($data);

        return $this->updateSuccess($coupon);
    }
}
