<?php

namespace App\Http\Controllers;

use App\Events\Chat\ChatMessageEvent;
use App\Events\Chat\ChatNotiEvent;
use App\Events\Chat\ChatTyping;
use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct
    (protected Chat $chatModel,
     protected Room $roomModel,
     protected User $userModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/chat-by-user/{phone}/{name}",
     *      operationId="getChatByUser",
     *      tags={"Chat"},
     *      summary="Get list of chat",
     *      description="Returns list of chat",
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
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       ),
     *     )
     */
    public function getChatByUser($phone)
    {
        $room_id = $this->getRoomByUser($phone);
        $list = $this->getMessageByRoom($room_id);

        $this->chatModel->newQuery()
            ->where('room_id', $room_id)
            ->where('sender_phone', '!=', $phone)
            ->update(['isSeen' => true]);

        return response()->json([
            'data' => $list,
            'room_id' => $room_id
        ], 200);
    }

    public function getRoomByUser($phone)
    {
        $userExitsInRoom = $this->roomModel
            ->newQuery()
            ->where('user_phone', $phone)
            ->first();
        return $userExitsInRoom->id;
    }

    public function getMessageByRoom($roomId)
    {
        return $this->chatModel
            ->newQuery()
            ->with(['sender', 'room'])
            ->where('room_id', $roomId)
            ->get();
    }


    /**
     * @OA\Post(
     *      path="/chat",
     *      operationId="createChat",
     *      tags={"Chat"},
     *      summary="Create new chat",
     *      description="Returns chat data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ChatCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       ),
     * )
     */
    public function store(Request $request)
    {
        $data = [
            'content' => $request->message,
            'room_id' => $request->room_id,
            'sender_phone' => $request->phone,
            'isSeen' => false
        ];

        $item = $this->chatModel
            ->newQuery()
            ->create($data);
        event(new ChatMessageEvent($item));
        event(new ChatNotiEvent($this->getListRoomChatAdmin()));

//        $messageNotSeen = count(
//            $this->chatModel
//                ->newQuery()
//                ->where('sender_phone', $request->phone)
//                ->where('room_id', $request->room_id)
//                ->where('isSeen', false)
//                ->get());
//        $userPhone = $this->roomModel->newQuery()->where('id', $request->room_id)->first()->user_phone;
//
//        broadcast(new CountMessageNotSeenByUser($messageNotSeen, $userPhone))->toOthers();
    }

    public function getListRoomChatAdmin()
    {
        return $this->roomModel->newQuery()->get();
    }

    /**
     * @OA\Get(
     *      path="/admin/chat-by-room/{id}",
     *      operationId="getChatByRoom",
     *      tags={"Chat"},
     *      summary="Get chat information",
     *      description="Returns chat data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       ),
     * )
     */
    public function getChatByRoom(int $id)
    {
        $data = $this->getMessageByRoom($id);

        $ids = $data->pluck('id');
        $this->chatModel->newQuery()
            ->whereIn('id', $ids)
            ->where('sender_phone', '!=', Auth::user()->phone)
            ->update(['isSeen' => true]);

        event(new ChatNotiEvent($this->getListRoomChatAdmin()));

        return response()->json($data, 200);
    }


    /**
     * @OA\Post(
     *      path="/chat/typing",
     *      operationId="typingChat",
     *      tags={"Chat"},
     *      summary="Typing chat realtime",
     *      description="Returns Typing chat realtime",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ChatTyping")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       ),
     * )
     */
    public function onTypingChat(Request $request)
    {
        event(new ChatTyping($request->typing));
    }


}
