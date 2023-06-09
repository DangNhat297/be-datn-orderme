<?php

namespace App\Http\Controllers\Client;

use App\Events\Chat\ChatMessageEvent;
use App\Events\Notification\OrderNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\Dishes;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order            $order,
        protected Dishes           $dish,
        protected Cart             $cart,
        protected UserService      $userService,
        protected PaymentService   $paymentService,
        protected Payment          $payment,
        protected Chat             $chatModel,
        protected Notification     $notification,
        protected User             $user,
        protected UserNotification $userNotification,
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
            'name',
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

        $dishOfOrder = collect($request->dishes)->keyBy('dish_id');

        $res = DB::transaction(function () use ($data, $dishOfOrder, $request) {
            $order = $this->order
                ->newQuery()
                ->create($data);

            $order->dishes()->attach($dishOfOrder);

            $this->newMessage(1, $request->phone, $order);

            $this->newNotice($request->phone, $order->id);

            collect($request->dishes)->each(function ($dish) use ($order) {
                $this->dish
                    ->newQuery()
                    ->where('id', $dish['dish_id'])
                    ->first()
                    ->decrement('quantity', $dish['quantity']);
            });

            //log
            $order->logs()->create([
                'status' => 1,
                'change_by' => auth()->id ?? null
            ]);

            $order->coupon()->decrement('quantity');

            return $order;
        });

        if (!is_null($res) && $request->payment_method == 2) {
            $vnpay = $this->paymentService->createVNP($res->code, $res->total, $request);

            $res->update([
                'payment_url' => $vnpay['vnp_url']
            ]);

            return $vnpay;
        }

        return $res;
    }

    public function newMessage($status, $phoneUser, $order, $content = null)
    {
        $contentDefault = "Cảm ơn bạn đã đặt hàng. Món ngon " . $order->code . " của bạn: " . OrderLog::textLog[$status];
        $phoneAdmin = '0987654321';
        $roomByCurrentUser = Room::where('user_phone', $phoneUser)->first();
        $msg = [
            'content' => $content ?? $contentDefault,
            'room_id' => $roomByCurrentUser->id,
            'sender_phone' => $phoneAdmin,
            'isSeen' => false
        ];
        $newMsg = $this->chatModel->newQuery()->create($msg);
        event(new ChatMessageEvent($newMsg));
        return true;
    }

    public function newNotice($actorPhone, $orderId, $msg = 'Đã đặt hàng vào lúc')
    {
        $newNotification = [
            'user_phone' => $actorPhone,
            'message_template' => $msg,
            'redirect_url' => env('ADMIN_URL') . '/order/' . $orderId,
            'type' => 'order',
        ];
        $notice = $this->notification->newQuery()->create($newNotification);
        $listAdmin = $this->user->newQuery()->where('role', 'admin')->get()->pluck('id');
        $listAdmin->each(function ($item) use ($notice, $newNotification) {
            $newData = $this->userNotification->newQuery()->create([
                'notification_id' => $notice->id,
                'recipient_id' => $item
            ]);
            broadcast(new OrderNotification(
                $newData,
                $newData->load('user'),
                $notice
            ))->toOthers();
        });
        return true;
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
     *          @OA\JsonContent(ref="#/components/schemas/OrderUpdateClient")
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
            'change_by' => $request->phone
        ]);
        $content = "Ôi thật là tiếc. Món ngon " . $request->code . " không thể đến với bạn rồi: " . OrderLog::textLog[0] . " :((";
        $this->newMessage(0, $request->phone, $request, $content);

        if ($order->payment_method == ORDER_PAYMENT_VNPAY && $order->payment_status == ORDER_PAYMENT_SUCCESS) {
            $this->newNotice($request->phone, $order->id, 'Đã hủy hơn hàng và yêu cầu hoàn tiền vào lúc');
        } else {
            $this->newNotice($request->phone, $order->id, 'Đã hủy hơn hàng vào lúc');
        }

        return $this->updateSuccess($order);
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
            'logs',
            'location'
        ]);

        $order->dishes->transform(function ($dish) {
            $dish->quantity = $dish->pivot->quantity;
            $dish->price = $dish->pivot->price;
            $dish->price_sale = $dish->pivot->price_sale ?? 0;

            return $dish->only([
                'id',
                'name',
                'slug',
                'image',
                'price',
                'quantity',
                'price_sale'
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

    public function payment(Request $request, $order)
    {
        $order = $this->order
            ->newQuery()
            ->where('code', $order)
            ->firstOrFail();

        return $this->paymentService->createVNP($order->code, $order->total, $request);
    }

    // ipn url, after payment, save to db

    public function returnPaymentVNP(Request $request)
    {
        $order = $this->order
            ->newQuery()
            ->where('code', $request->vnp_TxnRef)
            ->firstOrFail();

        if ($order->payment_status == 0) {
            if ($order->total == ($request->vnp_Amount / 100)) {
                if ($request->vnp_ResponseCode == '00' || $request->vnp_TransactionStatus == '00') {
                    $order->update([
                        'payment_status' => ORDER_PAYMENT_SUCCESS,
                        'payment_url' => null
                    ]);
                    $res = [
                        'RspCode' => '00',
                        'Message' => 'Thanh toán thành công'
                    ];
                } else {
                    $res = [
                        'RspCode' => '99',
                        'Message' => 'Lỗi không xác định'
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
                        'message' => $res['Message']
                    ]);
            } else {
                $res = [
                    'RspCode' => '04',
                    'Message' => 'Sai giá'
                ];
            }
        } else {
            $res = [
                'RspCode' => '02',
                'Message' => 'Đơn hàng đã được thanh toán'
            ];
        }

        return $res;
    }
}
