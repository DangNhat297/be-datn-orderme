<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryModel;

    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    /**
     * @OA\Get(
     *      path="/client/category",
     *      operationId="getClientCategories",
     *      tags={"CategoryClient"},
     *      summary="Get list of category",
     *      description="Returns list of category",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="Category name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          description="Category status",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
            ->where('status', ENABLE)
            ->findByName($request)
            ->findByStatus($request)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);

        $data->getCollection()->transform(function ($value) {
            $value->makeHidden(['created_at', 'updated_at', 'status']);
            return $value;
        });

        return $this->sendSuccess($data);
    }

}
