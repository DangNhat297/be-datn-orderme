<?php

namespace App\Http\Controllers\Shipper;

use App\Events\Chat\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected Order $order,
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/shipper/order",
     *      operationId="getOrdersByShipper",
     *      tags={"Shipper Order"},
     *      summary="Get list of order",
     *      description="Returns list of order",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="Can search by order code, user name",
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
     *          description="can sort by multiple field vd :-id,+id,+name,-name,-code, +code",
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
            ->with(['location', 'user', 'dishes'])
            ->findByMultiple($request)
            ->findByStatus($request)
            ->findOrderBy($request)
            ->whereHas('location', function ($q) {
                return $q->where('distance', '!=', 0);
            })
            ->whereIn('status', [ORDER_WAIT_FOR_SHIPPING, ORDER_SHIPPING, ORDER_COMPLETE])
            ->paginate($pageSize);

        return response()->json($orders, 200);
    }


    /**
     * @OA\Get(
     *      path="/shipper/order/{id}",
     *      operationId="getOrderShipperById",
     *      tags={"Shipper Order"},
     *      summary="Get order information",
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
    public function show(int $id): JsonResponse
    {
        $item = $this->order
            ->newQuery()
            ->with(['location', 'user', 'dishes'])
            ->findOrFail($id);

        return response()->json($item, 200);
    }


    /**
     * @OA\Put(
     *      path="/shipper/order/{id}",
     *      operationId="updateOrderShipper",
     *      tags={"Shipper Order"},
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
        $listStatus = [ORDER_SHIPPING, ORDER_COMPLETE];
        $status = $request->status;

        if (in_array($status, $listStatus)) {
            $order->update(['status' => $status]);

            if ($request->status == ORDER_COMPLETE) {
                $order->update(['payment_status' => ORDER_PAYMENT_SUCCESS]);
            }

            $order->logs()->create([
                'status' => $status,
                'change_by' => auth()->id ?? null
            ]);

            return response()->json($order, 202);
        }
        $this->newMessage($status, $order->phone, $order);
        return response()->json([
            'message' => 'You can update status to shipping or complete'
        ], 500);

    }

    public function newMessage($status, $phoneUser, $order, $content = null)
    {
        $time = $status == 4 && " trong " . $order->location()->distance . " phút nữa sẽ được giao đến bạn !";

        $contentDefault = "Dự kiến, món ngon " . $order->code . " của bạn: " . OrderLog::textLog[$status] . $time;
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
}
