<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected  $locationModel;
    public function __construct( Location $locationModel)
    {
        $this->locationModel=$locationModel;
    }
    /**
     * @OA\Get(
     *      path="/client/location",
     *      operationId="getClientLocations",
     *      tags={"LocationClient"},
     *      summary="Get list of locltion",
     *      description="Returns list of location",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LocationResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->locationModel
            ->newQuery()
            ->paginate($request->limit??PAGE_SIZE_DEFAULT);
        $data ->makeHidden(['created_at', 'updated_at'])->toArray();
        return $this->sendSuccess($data);
    }

    /**
     * @OA\Get(
     *      path="/client/location/{id}",
     *      operationId="getClientLocationById",
     *      tags={"LocationClient"},
     *      summary="Get location information",
     *      description="Returns location data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Location id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LocationResponse")
     *       ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $item = $this->locationModel
            ->newQuery()
            ->findOrFail($id);
        $item ->makeHidden(['created_at', 'updated_at'])->toArray();
        return $this->sendSuccess($item);
    }


}
