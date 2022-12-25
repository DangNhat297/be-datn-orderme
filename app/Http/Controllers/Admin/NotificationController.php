<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function __construct(protected UserNotification $notify)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/notification",
     *      operationId="getNotifies",
     *      tags={"Notification"},
     *      summary="Get list of notification",
     *      description="Returns list of notification",
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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserNotificationResponse")
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?: PAGE_SIZE_DEFAULT;

        $data = $this->notify->newQuery()
            ->with(['notification', 'user'])
            ->where('recipient_id', auth()->id())
            ->findByDateRange($request)
            ->orderBy('created_at', 'desc')
            ->get();

        $notifyNotSeen = count($this->notify->newQuery()
            ->where('recipient_id', auth()->id())
            ->where('isSeen', false)
            ->get());

        return response()->json(
            [
                'data' => $data,
                'notifyNotSeen' => $notifyNotSeen
            ], 200);
    }

    /**
     * @OA\Put(
     *      path="/admin/multiple-seen-notice",
     *      operationId="seenMultipleNotification",
     *      tags={"Notification"},
     *      summary="Update existing Notification",
     *      description="Returns updated Notification data",
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResponse")
     *       )
     * )
     */
    public function multipleSeenNotice()
    {
        $this->notify->newQuery()
            ->where('recipient_id', auth()->id())
            ->update(['isSeen' => true]);
        return response()->json([], 200);
    }

    /**
     * @OA\Put(
     *      path="/admin/notification/{id}",
     *      operationId="seenNotification",
     *      tags={"Notification"},
     *      summary="Update existing Notification",
     *      description="Returns updated Notification data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Notification id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResponse")
     *       )
     * )
     */
    public function update(Request $request, $id)
    {
        $item = $this->notify->newQuery()->findOrFail($id);

        $item->update(['isSeen' => true]);
        return response()->json($item, 200);
    }

}
