<?php

namespace App\Http\Controllers;

use App\Events\Chat\ChatNotiEvent;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct(protected Room $roomModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/room",
     *      operationId="getRooms",
     *      tags={"Room"},
     *      summary="Get list of room",
     *      description="Returns list of room",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="user name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoomResponse")
     *       ),
     *     )
     */
    public function index(Request $request)
    {
        $data = $this->roomModel
            ->newQuery()
            ->findByName($request)
            ->get();

        event(new ChatNotiEvent($data));

//        return \response()->json($data, 200);
    }


    /**
     * @OA\Get(
     *      path="/room/message-not-seen-by-user/{phone}",
     *      operationId="getMessageNotSeenByUser",
     *      tags={"Room"},
     *      summary="Get room information",
     *      description="Returns room data",
     *      @OA\Parameter(
     *          name="phone",
     *          description="User Phone",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoomResponse")
     *       ),
     * )
     */
    public function getMessageNotSeenByUser($phone)
    {
        $roomByUser = Room::where('user_phone', $phone)->first();
        $messageNotSeen = count(Chat::where('sender_phone', '!=', $phone)
            ->where('room_id', $roomByUser->id)
            ->where('isSeen', false)
            ->get());

//        broadcast(new CountMessageNotSeenByUser($messageNotSeen, $phone))->toOthers();


        return response()->json($messageNotSeen, 200);
    }


}
