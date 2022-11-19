<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryModel;
    public function __construct( Category $categoryModel)
    {
        $this->categoryModel=$categoryModel;
    }

    /**
     * @OA\Get(
     *      path="/client/category",
     *      operationId="getClientCategories",
     *      tags={"CategoryClient"},
     *      summary="Get list of category",
     *      description="Returns list of category",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->categoryModel
            ->newQuery()
            ->where('is_deleted', 0)
            ->findByName($request)
            ->findByStatus($request)
            ->paginate($request->limit??PAGE_SIZE_DEFAULT);

        $data->getCollection()->transform(function ($value) {
            $value->makeHidden(['created_at', 'updated_at','is_deleted','status']);
            return $value;
        });

        return $this->sendSuccess($data);
    }

}
