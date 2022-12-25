<?php

namespace App\Http\Controllers\Chief;

use App\Events\Chat\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function response;

class OrderController extends Controller
{
    public function __construct(
        protected Order $order,
        protected Chat  $chatModel,
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/chief/order",
     *      operationId="getOrdersByChief",
     *      tags={"Chief Order"},
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
            ->with(['location', 'user', 'dishes', 'logs'])
            ->findByMultiple($request)
            ->findByStatus($request)
            ->findOrderBy($request)
            ->whereIn('status', [ORDER_PENDING, ORDER_COOKING, ORDER_WAIT_FOR_SHIPPING])
            ->paginate($pageSize);

        $orders->getCollection()->transform(function ($order) {
            $order->logs->transform(function ($log) {
                $log->title = OrderLog::textLog[$log->status];

                return $log;
            });
        });

        return response()->json($orders, 200);
    }

    /**
     * @OA\Get(
     *      path="/chief/order/{id}",
     *      operationId="getOrderChiefById",
     *      tags={"Chief Order"},
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
     *      path="/chief/order/{id}",
     *      operationId="updateOrderChief",
     *      tags={"Chief Order"},
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
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResponse")
     *       )
     * )
     */
    public function update(Request $request, Order $order)
    {
        $order->update(['status' => 3]);

        $order->logs()->create([
            'status' => 3,
            'change_by' => auth()->id ?? null
        ]);
        $this->newMessage(3, $order->phone, $order);
        return response()->json($order, 202);
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

}
