<?php

namespace App\Http\Controllers;

use App\Events\Chat\ChatMessageEvent;
use App\Events\Chat\ChatNotiEvent;
use App\Events\Chat\ChatTyping;
use App\Models\Chat;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected Chat $chatModel, protected Room $roomModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/chat-by-user",
     *      operationId="getChatByUser",
     *      tags={"Chat"},
     *      summary="Get list of chat",
     *      description="Returns list of chat",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       ),
     *     )
     */
    public function getChatByUser()
    {
        $data = $this->chatModel
            ->newQuery()
            ->with(['sender'])
            ->where('room_id', $this->getRoomByUser())
            ->get();

        event(new ChatMessageEvent($data));
    }

    public function getRoomByUser()
    {
        $userExitsInRoom = $this->roomModel
            ->newQuery()
            ->where('userId', auth()->id())
            ->first();
        if ($userExitsInRoom) {
            return $userExitsInRoom->id;
        }
        $data = ['userId' => auth()->id()];
        $item = $this->roomModel
            ->newQuery()
            ->create($data);
        return $item->id;
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
            'room_id' => $request->room_id ?? $this->getRoomByUser(),
            'sender_id' => auth()->id(),
            'isSeen' => false
        ];

        $item = $this->chatModel
            ->newQuery()
            ->create($data);

        $list = $this->getMessageByRoom($item->room_id);
        event(new ChatMessageEvent($list));

        event(new ChatNotiEvent($this->getListRoomChatAdmin()));
    }

    public function getMessageByRoom($roomId)
    {
        return $this->chatModel
            ->newQuery()
            ->with(['sender'])
            ->where('room_id', $roomId)
            ->get();
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
        event(new ChatMessageEvent($data));

        $ids = $data->pluck('id');
        $this->chatModel->newQuery()
            ->whereIn('id', $ids)
            ->update(['isSeen' => true]);

        event(new ChatNotiEvent($this->getListRoomChatAdmin()));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $item = $this->chatModel
            ->newQuery()
            ->findOrFail($id);

        $item->update($data);

        return $this->updateSuccess($item);
    }



    //    /**
//     * @OA\Put(
//     *      path="/chat/{id}",
//     *      operationId="updateChat",
//     *      tags={"Chat"},
//     *      summary="Update existing chat",
//     *      description="Returns updated chat data",
//     *      @OA\Parameter(
//     *          name="id",
//     *          description="Chat id",
//     *          required=true,
//     *          in="path",
//     *          @OA\Schema(
//     *              type="integer"
//     *          )
//     *      ),
//     *      @OA\RequestBody(
//     *          required=true,
//     *          @OA\JsonContent(ref="#/components/schemas/ChatUpdate")
//     *      ),
//     *      @OA\Response(
//     *          response=202,
//     *          description="Successful operation",
//     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
//     *       )
//     * )
//     */

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




//    /**
//     * @OA\Delete(
//     *      path="/chat/{id}",
//     *      operationId="deleteChat",
//     *      tags={"Chat"},
//     *      summary="Delete existing chat",
//     *      description="Deletes a record and returns no content",
//     *      @OA\Parameter(
//     *          name="id",
//     *          description="Chat id",
//     *          required=true,
//     *          in="path",
//     *          @OA\Schema(
//     *              type="integer"
//     *          )
//     *      ),
//     *       @OA\Response(
//     *          response=204,
//     *          description="Successful operation",
//     *          @OA\JsonContent()
//     *       )
//     * )
//     */

    public function destroy($id): JsonResponse
    {
        $this->chatModel
            ->newQuery()
            ->findOrFail($id)->delete();

        return $this->deleteSuccess();
    }
}
