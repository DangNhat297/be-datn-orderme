<?php

namespace App\Http\Controllers\Admin;

use App\Events\Chat\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Chat;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Program;
use App\Models\Room;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order          $order,
        protected Dishes         $dish,
        protected Chat           $chatModel,
        protected PaymentService $paymentService,
        protected Program           $program
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/order",
     *      operationId="getOrders",
     *      tags={"Order"},
     *      summary="Get list of order",
     *      description="Returns list of order",
     *      @OA\Parameter(
     *          name="keyword",
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
            ->findOrderBy($request)
            ->latest()
            ->paginate($pageSize);

        return $this->sendSuccess($orders);
    }


    /**
     * @OA\Post(
     *      path="/admin/order",
     *      operationId="createOrder",
     *      tags={"Order"},
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
            'payment_status'
        ]);

        $dishOfOrder = collect($request->dishes)->keyBy('dish_id');

        $res = DB::transaction(function () use ($data, $dishOfOrder) {
            $order = $this->order
                ->newQuery()
                ->create($data);

            //add chat
            $this->newMessage(1, $order->phone, $order);

            //log
            $order->dishes()->attach($dishOfOrder);

            $order->logs()->create([
                'status' => 1,
                'change_by' => auth()->id ?? null
            ]);

            $order->coupon()->decrement('quantity');

            return $order;
        });

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
        $order->load([
            'dishes',
            'logs'
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


        $order->logs()->create([
            'status' => $request->status,
            'change_by' => auth()->id ?? null
        ]);

        $this->newMessage($request->status, $order->phone, $order);

        return $this->updateSuccess($order);
    }


    public function refundVNP($id)
    {
        $order = $this->order
            ->newQuery()
            ->where('id', $id)
            ->where('payment_status', ORDER_PAYMENT_SUCCESS)
            ->where('payment_method', ORDER_PAYMENT_VNPAY)
            ->whereHas('payments', function ($q) {
                return $q->where('transaction_status', '00');
            })
            ->doesntHave('payments', 'and', function ($q) {
                return $q->where('transaction_status', '05');
            })
            ->firstOrFail();

        return $this->paymentService->refundRequest($order);
    }
}
