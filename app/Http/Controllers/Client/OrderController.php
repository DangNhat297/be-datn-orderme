<?php

namespace App\Http\Controllers\Client;

use App\Events\Chat\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order          $order,
        protected Dishes         $dish,
        protected Cart           $cart,
        protected UserService    $userService,
        protected PaymentService $paymentService,
        protected Payment        $payment,
        protected Chat           $chatModel,
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/client/order",
     *      operationId="getOrderListClient",
     *      tags={"Order Client"},
     *      summary="Get order list",
     *      description="Returns order data",
     *      @OA\Parameter(
     *          name="phone",
     *          description="user phone",
     *          required=true,
     *          in="query",
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
    public function index(Request $request)
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
            ->where('phone', $request->phone)
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
    public function store(OrderRequest $request)
    {
        $data = $request->only([
            'phone',
            'note',
            'location_id',
            'total',
            'price_sale',
            'price_none_sale',
            'coupon_id',
            'payment_method',
            'location_detail'
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

        $res = DB::transaction(function () use ($data, $dishOfOrder, $request) {
            $order = $this->order
                ->newQuery()
                ->create($data);

            $order->dishes()->attach($dishOfOrder);

            //add chat
            if (auth()->check()) {
                $this->newMessage();
            }

            //log
            $order->logs()->create([
                'status' => 1,
                'change_by' => auth()->id ?? null
            ]);

            $order->coupon()->decrement('quantity');

            return $order;
        });

        if (!is_null($res) && $request->payment_method == 2)
            return $this->paymentService->createVNP($res->code, $res->total, $request);

        return $res;
    }

    public function newMessage($status = 1)
    {
        $adminDefault = User::where('phone', '0987654321')->first();
        $roomByCurrentUser = Room::where('id', auth()->id())->first();
        $msg = [
            'content' => OrderLog::textLog[$status],
            'room_id' => $roomByCurrentUser->id,
            'sender_id' => $adminDefault->id,
        ];
        $newMsg = $this->chatModel->newQuery()->create($msg);
        event(new ChatMessageEvent(auth()->id(), $newMsg));
        return true;
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
    public function show($order)
    {
        $order = $this->order
                        ->newQuery()
                        ->where('id', $order)
                        ->orWhere('code', $order)
                        ->firstOrFail();

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function payment(Request $request, Order $order)
    {
        return $this->paymentService->createVNP($order->code, $order->total);
    }

    public function returnPaymentVNP(Request $request)
    {
        $order = $this->order
            ->newQuery()
            ->where('code', $request->vnp_TxnRef)
            ->firstOrFail();

        if ($order->payment_status == 0) {
            if ($order->total == ($request->vnp_Amount / 100)) {
                if ($request->vnp_ResponseCode == '00' || $request->vnp_TransactionStatus == '00') {
                    $order->update(['payment_status' => 1]);
                    $res = [
                        'RspCode' => '00',
                        'Message' => 'Confirm Success'
                    ];
                } else {
                    $res = [
                        'RspCode' => '99',
                        'Message' => 'Unknow error'
                    ];
                }

                $this->payment
                    ->newQuery()
                    ->create([
                        'order_code' => $request->vnp_TxnRef,
                        'payment_method' => 'VNPAY',
                        'amount' => $request->vnp_Amount,
                        'transaction_no' => $request->vnp_TransactionNo,
                        'transaction_status' => $request->vnp_TransactionStatus,
                        'bank_code' => $request->vnp_BankCode,
                        'card_type' => $request->vnp_CardType,
                        'message' => $res['message']
                    ]);
            } else {
                $res = [
                    'RspCode' => '04',
                    'Message' => 'invalid amount'
                ];
            }
        } else {
            $res = [
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ];
        }

        return $this->sendSuccess($res);
    }

    // ipn url, after payment, save to db

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
        if (auth()->check()) {
            $this->newMessage(0);
        }
        return $this->updateSuccess($order);
    }
}
