<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Dishes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(Dishes $dishes)
    {
        $this->dishes = $dishes;
    }


    /**
     * @OA\Get(
     *      path="/client/dish",
     *      operationId="getClientDishes",
     *      tags={"DishClient"},
     *      summary="Get list of dish",
     *      description="Returns list of dish",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->dishes
            ->newQuery()
            ->findbyName($request)
            ->findbyCategory($request)
            ->findSort($request)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);
        $data->makeHidden('status', 'created_at', 'updated_at');
        return $this->sendSuccess($data);
    }

    /**
     * @OA\Get(
     *      path="/client/dish/{id}",
     *      operationId="getClientDishById",
     *      tags={"DishClient"},
     *      summary="Get dish information",
     *      description="Returns dish data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dish id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       ),
     * )
     */
    public function show($id): JsonResponse
    {
        $item = $this->dishes
            ->newQuery()
            ->findOrFail($id);
        $item->makeHidden('status', 'created_at', 'updated_at')->toArray();
        return $this->sendSuccess($item);
    }

    /**
     * @OA\Get(
     *      path="/client/dish/by-category/{id}",
     *      operationId="getClientDishByCategory",
     *      tags={"DishClient"},
     *      summary="Get dish information",
     *      description="Returns dish data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       ),
     * )
     */

    public function by_category(Request $request, $id): JsonResponse
    {
        $item = $this->dishes
            ->newQuery()
            ->where('category_id', $id)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);

        $item->makeHidden('status', 'created_at', 'updated_at')->toArray();
        return $this->sendSuccess($item);
    }

}
