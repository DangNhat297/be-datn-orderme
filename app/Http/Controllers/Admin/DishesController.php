<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishesRequest;
use App\Http\Requests\DishesUpdateRequest;
use App\Models\Dishes;
use Illuminate\Http\JsonResponse;

class DishesController extends Controller
{
    protected $dishes;

    public function __construct(Dishes $dishes)
    {
        $this->dishes = $dishes;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * @OA\Get(
     *      path="/admin/dishes",
     *      operationId="getDishes",
     *      tags={"Dishes"},
     *      summary="Get list of dishes",
     *      description="Returns list of dishes",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DishesResponse")
     *       ),
     *     )
     */
    public function index(): JsonResponse
    {
        $data = $this->dishes
            ->newQuery()
            ->orderBy('id', 'DESC')
            ->paginate(PAGE_SIZE_DEFAULT);

        return $this->sendSuccess($data);

    }

    /**
     * @OA\Post(
     *      path="/admin/dishes",
     *      operationId="createDishes",
     *      tags={"Dishes"},
     *      summary="Create new dishes",
     *      description="Returns dishes data",
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
        $item->image="https://imgs.search.brave.com/M3uodhHUDZJcM4Fnl2sXDQ2UfMbPCLn-upkK4ZPbpkI/rs:fit:711:225:1/g:ce/aHR0cHM6Ly90c2U0/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5F/SGxQLU1rQnM0OHhx/T2FTaVZJdUZnSGFF/OCZwaWQ9QXBp";
//        if ($request->hasFile('image')) {
//            $file = $request->image;
//            $item->image = uploadFile($file, 'images/dishes/');
//        }

        $item->save();
        return $this->createSuccess($item);

    }

    /**
     * @OA\Get(
     *      path="/admin/dishes/{id}",
     *      operationId="getDishesById",
     *      tags={"Dishes"},
     *      summary="Get dishes information",
     *      description="Returns dishes data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dishes id",
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
        return $this->sendSuccess($item);
    }

    /**
     * @OA\Put(
     *      path="/admin/dishes/{id}",
     *      operationId="updateDishes",
     *      tags={"Dishes"},
     *      summary="Update existing dishes",
     *      description="Returns updated dishes data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dishes id",
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
            $file = $request->image;
            $fileCurrent = public_path() . '/' . $item->image;
            if (file_exists($item->image)) {
                unlink($fileCurrent);
            }
            $item->image = uploadFile($file, 'images/dishes/');
        } else {
            $item->image = $item->image;
        }
        $item->save();

        return $this->sendSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/admin/dishes/{id}",
     *      operationId="deleteDishes",
     *      tags={"Dishes"},
     *      summary="Delete existing dishes",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Dishes id",
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
        $fileCurrent = public_path() . '/' . $item->image;
        if (file_exists($item->image)) {
            unlink($fileCurrent);
        }
        $item->delete();

        return $this->deleteSuccess();
    }
}
