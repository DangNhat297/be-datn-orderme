<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserNotificationResponse")
     *       ),
     *     )
     */
    public function index()
    {
        $data = $this->notify->newQuery()
            ->with(['notification', 'user'])
            ->where('recipient_id', auth()->id())
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
