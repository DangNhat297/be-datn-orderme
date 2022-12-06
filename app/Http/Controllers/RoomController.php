<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function index(Request $request): JsonResponse
    {
        $data = $this->roomModel
            ->newQuery()
            ->with(['user'])
            ->findByName($request)
            ->get();

        return $this->sendSuccess($data);
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
