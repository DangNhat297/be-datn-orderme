<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishesRequest;
use App\Http\Requests\DishesUpdateRequest;
use App\Models\Dishes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Program;


class DishesController extends Controller
{
    protected $dishes;

    public function __construct(Dishes $dishes, Program $program)
    {
        $this->dishes = $dishes;
        $this->program = $program;
    }


    /**
     * @OA\Get(
     *      path="/admin/dish",
     *      operationId="getDishes",
     *      tags={"Dish"},
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
            ->findByPriceRange($request)
            ->findOrderBy($request)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);

        $currentFlashSale = $this->program
            ->newQuery()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', ENABLE)
            ->with('dishes')
            ->first();

        $data->getCollection()->transform(function ($dish) use ($currentFlashSale) {
            $dish->makeHidden(['created_at', 'updated_at', 'status']);

            if (isset($currentFlashSale->dishes) && $currentFlashSale->dishes->contains('id', $dish->id)) {
                $dishInFlashsale = $currentFlashSale->dishes()->where('dish_id', $dish->id)->first();
                $dish->price_sale = $dish->price - ($dish->price * ($dishInFlashsale->pivot->discount_percent / 100));
            }

            return $dish;
        });

        return $this->sendSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/admin/dish",
     *      operationId="createDish",
     *      tags={"Dish"},
     *      summary="Create new dish",
     *      description="Returns dish data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/DishesCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       ),
     * )
     */
    public function store(DishesRequest $request)
    {
        $item = $this->dishes->fill($request->all());
        $item->image = $request->image ?? fakeImage();
        $item->slug = $request->slug ?? Str::slug($request->name, '-');
        $item->save();
        return $this->createSuccess($item);
    }

    /**
     * @OA\Get(
     *      path="/admin/dish/{id}",
     *      operationId="getDishById",
     *      tags={"Dish"},
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

        $currentFlashSale = $this->program
            ->newQuery()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', ENABLE)
            ->with('dishes')
            ->first();

        if (isset($currentFlashSale->dishes) && $currentFlashSale->dishes->contains('id', $item->id)) {
            $dishInFlashsale = $currentFlashSale->dishes()->where('dish_id', $item->id)->first();
            $item->price_sale = $item->price - ($item->price * ($dishInFlashsale->pivot->discount_percent / 100));
        }

        return $this->sendSuccess($item);
    }

    /**
     * @OA\Put(
     *      path="/admin/dish/{id}",
     *      operationId="updateDish",
     *      tags={"Dish"},
     *      summary="Update existing dish",
     *      description="Returns updated dish data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dish id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/DishesUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       )
     * )
     */
    public function update(DishesUpdateRequest $request, $id): JsonResponse
    {

        $item = $this->dishes->findOrFail($id);
        $item->fill($request->except('image'));

        if ($request->image) {
            //            $file = $request->image;
            //            $fileCurrent = public_path() . '/' . $item->image;
            //            if (file_exists($item->image)) {
            //                unlink($fileCurrent);
            //            }
            //                uploadFile($file, 'images/dishes/');
            $imageFake = fakeImage();
            $item->image = $request->image ?? $imageFake;
        } else {
            $item->image = $item->image;
        }
        $item->save();

        return $this->updateSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/admin/dish/{id}",
     *      operationId="deleteDish",
     *      tags={"Dish"},
     *      summary="Delete existing dish",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dish id",
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
        $item = $this->dishes
            ->newQuery()
            ->findOrFail($id);
        //        $fileCurrent = public_path() . '/' . $item->image;
        //        if (file_exists($item->image)) {
        //            unlink($fileCurrent);
        //        }
        $item->update(['status' => DISABLE]);

        return $this->deleteSuccess();
    }
}
