<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\OrderLog;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order       $order,
        protected Dishes      $dish,
        protected Cart        $cart,
        protected UserService $userService,
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/client/orderList/{phone}",
     *      operationId="getOrderListClient",
     *      tags={"Order Client"},
     *      summary="Get order list",
     *      description="Returns order data",
     *      @OA\Parameter(
     *          name="phone",
     *          description="user phone",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResponse")
     *       ),
     * )
     */
    public function index($phone, Request $request)
    {

        //        if (auth()->check()) {
        //            $orders = $this->order
        //                ->newQuery()
        //                ->where('user_id', auth()->id)
        //                ->latest()
        //                ->get();
        //
        //            return $this->sendSuccess($orders);
        //        }
        //
        //        $phone = $this->userService->getInfoGuest();

        $orders = $this->order
            ->newQuery()
            ->with(['location'])
            ->where('phone', $phone)
            ->latest()
            ->get();

        return $this->sendSuccess($orders);
    }


    /**
     * @OA\Post(
     *      path="/client/order",
     *      operationId="createOrderClient",
     *      tags={"Order Client"},
     *      summary="Create new order",
     *      description="Returns order data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResponse")
     *       ),
     * )
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'phone',
            'note',
            'location_id',
            'total',
            'payment_method'
        ]);

        $data['payment_status'] = ORDER_PAYMENT_WAITING;

        $dishIDs = collect($request->dishes)->pluck('dish_id')->toArray();

        $dishes = $this->dish
            ->newQuery()
            ->findMany($dishIDs);

        $dishOfOrder = collect($request->dishes)->map(function ($dish) use ($dishes) {
            $dish['price'] = $dishes->find($dish['dish_id'])->price;

            return $dish;
        })->keyBy('dish_id');

        // $data['total'] = $dishOfOrder->reduce(function ($sum, $currentVal) {
        //     return $sum += $currentVal['price'];
        // }, 0);

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
     *      path="/client/order/{id}",
     *      operationId="getOrderByIdClient",
     *      tags={"Order Client"},
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
        $order->load([
            'dishes',
            'logs'
        ]);

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

        $order->logs->transform(function ($log) {
            $log->title = OrderLog::textLog[$log->status];

            return $log;
        });

        return $this->sendSuccess($order);
    }


    /**
     * @OA\Put(
     *      path="/client/order/{id}",
     *      operationId="updateOrderClient",
     *      tags={"Order Client"},
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
        $order->update(['status' => 0]);

        $order->logs()->create([
            'status' => 0,
            'change_by' => auth()->id ?? null
        ]);

        return $this->updateSuccess($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
