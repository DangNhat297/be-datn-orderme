<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Dishes;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(Dishes $dishes, Program $program)
    {
        $this->dishes = $dishes;
        $this->program = $program;
    }


    /**
     * @OA\Get(
     *      path="/client/dish",
     *      operationId="getClientDishes",
     *      tags={"DishClient"},
     *      summary="Get list of dish",
     *      description="Returns list of dish",
     *      @OA\Parameter(
     *          name="category",
     *          description="category slug",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *        @OA\Parameter(
     *          name="keyword",
     *          description="dish name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          description="limit size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page size ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="start_price",
     *          description=" start price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="number"),
     *      ),
     *      @OA\Parameter(
     *          name="end_price",
     *          description=" end price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="number"),
     *      ),
     *      @OA\Parameter(
     *          name="orderBy",
     *          description=" sort by query vd :-id,+id,+name,-name,-price,+price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="string")
     *          ),
     *      ),
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
            ->findbyCategory($request)
            ->findbyName($request)
            ->findOrderBy($request)
            ->findByPriceRange($request)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);

        $currentFlashSales = $this->program
            ->newQuery()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', ENABLE)
            ->with('dishes')
            ->first();

        $data->getCollection()->transform(function ($dish)use ($currentFlashSales){
            $dish->makeHidden(['created_at', 'updated_at', 'status']);

            if (isset($currentFlashSales->dishes) && $currentFlashSales->dishes->contains('id', $dish->id)) {
                $dish->price_sale = $dish->price - ($dish->price*($currentFlashSales->discount_percent/100));
            }

            return $dish;
        });
        
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

        $currentFlashSales = $this->program
            ->newQuery()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', ENABLE)
            ->with('dishes')
            ->first();

        if (isset($currentFlashSales->dishes) && $currentFlashSales->dishes->contains('id', $item->id)) {
            $item->price_sale = $item->price - ($item->price*($currentFlashSales->discount_percent/100));
        }
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
