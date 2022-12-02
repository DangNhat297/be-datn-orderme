<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    public function __construct(protected Room $roomModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/room",
     *      operationId="getRooms",
     *      tags={"Room"},
     *      summary="Get list of room",
     *      description="Returns list of room",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoomResponse")
     *       ),
     *     )
     */
    public function index(): JsonResponse
    {
        $data = $this->roomModel
            ->newQuery()
            ->with(['user'])
            ->get();

        return $this->sendSuccess($data);
    }

    /**
     * @OA\Get(
     *      path="/room-by-user",
     *      operationId="getRoomByUser",
     *      tags={"Room"},
     *      summary="get room and message by user",
     *      description="Returns room and message data",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoomResponse")
     *       ),
     * )
     */
    public function getRoomByUser(): JsonResponse
    {
        $userExitsInRoom = $this->roomModel
            ->newQuery()
            ->where('userId', auth()->id())
            ->with(['user'])
            ->first();
        if ($userExitsInRoom) {
            return $this->createSuccess(['room_id' => $userExitsInRoom->id]);
        }
        $data = [
            'userId' => auth()->id()
        ];
        $item = $this->roomModel
            ->newQuery()
            ->create($data);
        return $this->createSuccess(['room_id' => $item->id]);

    }


//    /**
//     * @OA\Get(
//     *      path="/room/{id}",
//     *      operationId="getRoomById",
//     *      tags={"Room"},
//     *      summary="Get room information",
//     *      description="Returns room data",
//     *      @OA\Parameter(
//     *          name="id",
//     *          description="Room id",
//     *          required=true,
//     *          in="path",
//     *          @OA\Schema(
//     *              type="integer"
//     *          )
//     *      ),
//     *      @OA\Response(
//     *          response=200,
//     *          description="Successful operation",
//     *          @OA\JsonContent(ref="#/components/schemas/RoomResponse")
//     *       ),
//     * )
//     */
    public function show(int $id): JsonResponse
    {
        $item = $this->roomModel
            ->newQuery()
            ->findOrFail($id);

        return $this->sendSuccess($item);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Room $room
     * @return Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
