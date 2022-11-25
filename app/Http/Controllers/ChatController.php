<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected Chat $chatModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/chat",
     *      operationId="getChats",
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
    public function index(): JsonResponse
    {
        $data = $this->chatModel
            ->newQuery()
            ->with(['receiver', 'sender'])
            ->get();

        return $this->sendSuccess($data);
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
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        $item = $this->chatModel
            ->newQuery()
            ->create($data);

        return $this->createSuccess($item);
    }

    /**
     * @OA\Get(
     *      path="/chat/{id}",
     *      operationId="getChatById",
     *      tags={"Chat"},
     *      summary="Get chat information",
     *      description="Returns chat data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat id",
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
    public function show(int $id): JsonResponse
    {
        $item = $this->chatModel
            ->newQuery()
            ->findOrFail($id);

        return $this->sendSuccess($item);
    }


    /**
     * @OA\Put(
     *      path="/chat/{id}",
     *      operationId="updateChat",
     *      tags={"Chat"},
     *      summary="Update existing chat",
     *      description="Returns updated chat data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ChatUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatResponse")
     *       )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $item = $this->chatModel
            ->newQuery()
            ->findOrFail($id);

        $item->update($data);

        return $this->updateSuccess($item);
    }


    /**
     * @OA\Delete(
     *      path="/chat/{id}",
     *      operationId="deleteChat",
     *      tags={"Chat"},
     *      summary="Delete existing chat",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $this->chatModel
            ->newQuery()
            ->findOrFail($id)->delete();

        return $this->deleteSuccess();
    }
}
