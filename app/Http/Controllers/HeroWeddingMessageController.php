<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeroWedding\MessageCreateRequest;
use App\Models\HeroWeddingMessage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HeroWeddingMessageController extends Controller
{
    protected $heroMessage;

    public function __construct(HeroWeddingMessage $heroMessage)
    {
        $this->heroMessage = $heroMessage;
    }

    /**
     * @OA\Get(
     *      path="/hero-wedding/message",
     *      operationId="heroGetMessages",
     *      tags={"Hero Wedding Message"},
     *      summary="Get list of Message",
     *      description="Returns list of Message",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MessageResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->heroMessage
            ->newQuery()
            ->paginate(PAGE_SIZE_DEFAULT);

        return $this->sendSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/hero-wedding/message",
     *      operationId="heroAddMessage",
     *      tags={"Hero Wedding Message"},
     *      summary="Create new message",
     *      description="Returns message data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/MessageCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MessageResponse")
     *       ),
     * )
     */
    public function store(MessageCreateRequest $request): JsonResponse
    {
        try {
            $item = $this->heroMessage
                ->newQuery()
                ->create($request->all());

            return $this->createSuccess($item);
        } catch (Exception $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function create(MessageCreateRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @paramMessage $heroWeddingMessage
     * @return Response
     */
    public function show(HeroWeddingMessage $heroWeddingMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HeroWeddingMessage $heroWeddingMessage
     * @return Response
     */
    public function edit(HeroWeddingMessage $heroWeddingMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param HeroWeddingMessage $heroWeddingMessage
     * @return Response
     */
    public function update(Request $request, HeroWeddingMessage $heroWeddingMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HeroWeddingMessage $heroWeddingMessage
     * @return Response
     */
    public function destroy(HeroWeddingMessage $heroWeddingMessage)
    {
        //
    }
}
