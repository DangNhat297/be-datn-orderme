<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected Category $categoryModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/category",
     *      operationId="getCategories",
     *      tags={"Category"},
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
            ->where('parent_id', 0)
            ->findByName($request)
            ->findByStatus($request)
            ->paginate(PAGE_SIZE_DEFAULT);

        $data->getCollection()->transform(function ($value) {
            $value->makeHidden(['created_at', 'updated_at']);
            return $value;
        });

        return $this->sendSuccess($data);
    }
    /**
     * @OA\Post(
     *      path="/admin/category",
     *      operationId="createCategory",
     *      tags={"Category"},
     *      summary="Create new category",
     *      description="Returns category data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CategoryCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *       ),
     * )
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->only('name', 'slug', 'parent_id', 'status');

        $item = $this->categoryModel
            ->newQuery()
            ->create($data);

        return $this->createSuccess($item);
    }

    /**
     * @OA\Get(
     *      path="/admin/category/{id}",
     *      operationId="getCategoryById",
     *      tags={"Category"},
     *      summary="Get category information",
     *      description="Returns category data",
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
     *          @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *       ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $item = $this->categoryModel
            ->newQuery()
            ->with('children')
            ->findOrFail($id);

        return $this->sendSuccess($item);
    }

    /**
     * @OA\Put(
     *      path="/admin/category/{id}",
     *      operationId="updateCategory",
     *      tags={"Category"},
     *      summary="Update existing category",
     *      description="Returns updated category data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CategoryUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *       )
     * )
     */
    public function update(CategoryUpdateRequest $request, $id): JsonResponse
    {
        $data = $request->only(['name', 'slug', 'parent_id', 'status']);
        $item = $this->categoryModel
            ->newQuery()
            ->findOrFail($id);

        $item->update($data);

        return $this->updateSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/admin/category/{id}",
     *      operationId="deleteCategory",
     *      tags={"Category"},
     *      summary="Delete existing category",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
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
        $item = $this->categoryModel
            ->newQuery()
            ->findOrFail($id);

        $item->update(['is_deleted' => 1]);

        return $this->deleteSuccess();
    }
}
