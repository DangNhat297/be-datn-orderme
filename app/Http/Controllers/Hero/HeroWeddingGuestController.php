<?php

namespace App\Http\Controllers\Hero;

use App\Http\Controllers\Controller;
use App\Models\HeroWeddingGuest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HeroWeddingGuestController extends Controller
{

    protected $heroGuest;

    public function __construct(HeroWeddingGuest $heroGuest)
    {
        $this->heroGuest = $heroGuest;
    }

    /**
     * @OA\Get(
     *      path="/hero-wedding/guest",
     *      operationId="heroGetGuest",
     *      tags={"Hero Wedding Guest"},
     *      summary="Get list of Guest",
     *      description="Returns list of Guest",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="slug of guest ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GuestResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->heroGuest
            ->newQuery()
            ->findByHeroSlug($request)
            ->paginate(PAGE_SIZE_DEFAULT);

        return $this->sendSuccess($data);
    }


    /**
     * @OA\Post(
     *      path="/hero-wedding/guest",
     *      operationId="heroAddGuest",
     *      tags={"Hero Wedding Guest"},
     *      summary="Create new guest",
     *      description="Returns guest data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/GuestCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GuestResponse")
     *       ),
     * )
     */
    public function store(Request $request)
    {
        try {
            $item = $this->heroGuest
                ->newQuery()
                ->create($request->all());

            return $this->createSuccess($item);
        } catch (Exception $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/hero-wedding/guest/{id}",
     *      operationId="heroGetGuestById",
     *      tags={"Hero Wedding Guest"},
     *      summary="Get Guest information",
     *      description="Returns Guest data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Guest id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GuestResponse")
     *       ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $item = $this->heroGuest
            ->newQuery()
            ->findOrFail($id);

        return $this->sendSuccess($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HeroWeddingGuest $heroWeddingGuest
     * @return Response
     */
    public function edit(HeroWeddingGuestController $heroWeddingGuest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param HeroWeddingGuest $heroWeddingGuest
     * @return Response
     */
    public function update(Request $request, HeroWeddingGuestController $heroWeddingGuest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HeroWeddingGuest $heroWeddingGuest
     * @return Response
     */
    public function destroy(HeroWeddingGuestController $heroWeddingGuest)
    {
        //
    }
}
