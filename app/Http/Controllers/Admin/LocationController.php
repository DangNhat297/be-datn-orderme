<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationCreateRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use Illuminate\Http\JsonResponse;


class LocationController extends Controller
{
    public function __construct(protected Location $locationModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/location",
     *      operationId="getLocations",
     *      tags={"Location"},
     *      summary="Get list of locltion",
     *      description="Returns list of location",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LocationResponse")
     *       ),
     *     )
     */
    public function index(): JsonResponse
    {
        $data = $this->locationModel
            ->newQuery()
            ->paginate(PAGE_SIZE_DEFAULT);

        return $this->sendSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/admin/location",
     *      operationId="createLocation",
     *      tags={"Location"},
     *      summary="Create new location",
     *      description="Returns location data",
     *      security={{ "tokenJWT": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LocationCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LocationResponse")
     *       ),
     * )
     */
    public function store(LocationCreateRequest $request): JsonResponse
    {
        $data = $request->only('address', 'distance');

        $item = $this->locationModel
            ->newQuery()
            ->create($data);

        return $this->createSuccess($item);
    }

    /**
     * @OA\Get(
     *      path="/admin/location/{id}",
     *      operationId="getLocationById",
     *      tags={"Location"},
     *      summary="Get location information",
     *      description="Returns location data",
     *      security={{ "tokenJWT": {} }},
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

        return $this->sendSuccess($item);
    }

    /**
     * @OA\Put(
     *      path="/admin/location/{id}",
     *      operationId="updateLocation",
     *      tags={"Location"},
     *      summary="Update existing location",
     *      description="Returns updated location data",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Location id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LocationUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LocationResponse")
     *       )
     * )
     */
    public function update(LocationUpdateRequest $request, $id): JsonResponse
    {
        $data = $request->only(['address', 'distance']);
        $item = $this->locationModel
            ->newQuery()
            ->findOrFail($id);

        $item->update($data);

        return $this->updateSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/admin/location/{id}",
     *      operationId="deleteLocation",
     *      tags={"Location"},
     *      summary="Delete existing location",
     *      description="Deletes a record and returns no content",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Location id",
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
        $this->locationModel
            ->newQuery()
            ->findOrFail($id)->delete();

        return $this->deleteSuccess();
    }
}
