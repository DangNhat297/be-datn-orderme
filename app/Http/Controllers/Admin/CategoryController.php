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
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->categoryModel
            ->newQuery()
            ->where('is_deleted', 0)
            ->where('parent_id', 0)
            ->paginate(PAGE_SIZE_DEFAULT);

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
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
