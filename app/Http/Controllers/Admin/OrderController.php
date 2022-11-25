<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dishes;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order  $order,
        protected Dishes $dish,
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/order",
     *      operationId="getOrders",
     *      tags={"Order"},
     *      summary="Get list of order",
     *      description="Returns list of order",
     *      @OA\Parameter(
     *          name="search",
     *          description="code of order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="start_date",
     *          description="start date of order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="end_date",
     *          description="end date of order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="limit size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          description="status of order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResponse")
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?: PAGE_SIZE_DEFAULT;

        $orders = $this->order
            ->newQuery()
            ->with(['location', 'user'])
            ->findByCode($request)
            ->findByStatus($request)
            ->findByDateRange($request)
            ->latest()
            ->paginate($pageSize);

        return $this->sendSuccess($orders);
    }


    public function store(Request $request)
    {
        $data = $request->only([
            'phone',
            'note',
            'location_id'
        ]);

        $dishIDs = collect($request->dishes)->pluck('dish_id')->toArray();

        $dishes = $this->dish
            ->newQuery()
            ->findMany($dishIDs);

        $dishOfOrder = collect($request->dishes)->map(function ($dish) use ($dishes) {
            $dish['price'] = $dishes->find($dish['dish_id'])->price;

            return $dish;
        })->keyBy('dish_id');

        $data['total'] = $dishOfOrder->reduce(function ($sum, $currentVal) {
            return $sum += $currentVal['price'];
        }, 0);

        $res = DB::transaction(function () use ($data, $dishOfOrder) {
            $order = $this->order
                ->newQuery()
                ->create($data);

            $order->dishes()->attach($dishOfOrder);

            $order->logs()->create([
                'status' => 1,
                'change_by' => auth()->id ?? null
            ]);

            return $order;
        });

        return $res;
    }


    /**
     * @OA\Get(
     *      path="/admin/order/{id}",
     *      operationId="getOrderByIdAdmin",
     *      tags={"Order"},
     *      summary="Get order detail information",
     *      description="Returns order data",
     *      @OA\Parameter(
     *          name="id",
     *          description="order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderDetailResponse")
     *       ),
     * )
     */
    public function show(Order $order)
    {
        $order->load('dishes');

        $order->dishes->transform(function ($dish) {
            $dish->quantity = $dish->pivot->quantity;
            $dish->price = $dish->pivot->price;

            return $dish->only([
                'id',
                'name',
                'slug',
                'image',
                'price',
                'quantity'
            ]);
        });

        return $this->sendSuccess($order);
    }

    /**
     * @OA\Put(
     *      path="/admin/order/{id}",
     *      operationId="updateOrderAdmin",
     *      tags={"Order"},
     *      summary="Update existing Order",
     *      description="Returns updated location data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResponse")
     *       )
     * )
     */
    public function update(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);

        return $this->updateSuccess($order);
    }


    public function destroy(Order $order)
    {
        //
    }
}
