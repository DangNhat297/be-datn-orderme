<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishesRequest;
use App\Http\Requests\DishesUpdateRequest;
use App\Models\Dishes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * @OA\Get(
     *      path="/admin/dish",
     *      operationId="getDishes",
     *      tags={"Dish"},
     *      summary="Get list of dish",
     *      description="Returns list of dish",
     *      security={{ "tokenJWT": {} }},
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
     *      path="/admin/dish",
     *      operationId="createDish",
     *      tags={"Dish"},
     *      summary="Create new dish",
     *      description="Returns dish data",
     *      security={{ "tokenJWT": {} }},
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
        $imageFake = fakeImage();
        $item->image = $request->image ?? $imageFake;
        $item->slug = $request->slug ?? makeSlug($request->name);
//        if ($request->hasFile('image')) {
//            $file = $request->image;
//            $item->image = uploadFile($file, 'images/dishes/');
//        }

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
     *      security={{ "tokenJWT": {} }},
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
        return $this->sendSuccess($item);
    }

    /**
     * @OA\Put(
     *      path="/admin/dish/{id}",
     *      operationId="updateDish",
     *      tags={"Dish"},
     *      summary="Update existing dish",
     *      description="Returns updated dish data",
     *      security={{ "tokenJWT": {} }},
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
     *      security={{ "tokenJWT": {} }},
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
        $item->delete();

        return $this->deleteSuccess();
    }
}
