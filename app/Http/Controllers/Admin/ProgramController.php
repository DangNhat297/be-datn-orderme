<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(
        protected Program $program
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/program",
     *      operationId="getPrograms",
     *      tags={"Program"},
     *      summary="Get list of program",
     *      description="Returns list of program",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="Search by name flasale",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          description="program status",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="orderBy",
     *          description="nhiều trường",
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
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $page_size = $request->per_page ?: PAGE_SIZE_DEFAULT;

        $programs = $this->program
            ->newQuery()
            ->findByTitle($request)
            ->findByStatus($request)
            ->paginate($page_size);

        return $this->sendSuccess($programs);
    }

    /**
     * @OA\Post(
     *      path="/admin/program",
     *      operationId="createProgram",
     *      tags={"Program"},
     *      summary="Create new program",
     *      description="Returns program data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProgramCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       ),
     * )
     */
    public function store(ProgramRequest $request): JsonResponse
    {
        $data = $request->only([
            'title',
            'banner',
            'description',
            'status',
            'discount_percent',
            'start_date',
            'end_date',
        ]);

        $dishesId = $request->dish_ids;

        $program = $this->program
            ->newQuery()
            ->create($data);
        $program->dishes()->attach($dishesId);

        return $this->createSuccess($program);
    }

    /**
     * @OA\Get(
     *      path="/admin/program/{id}",
     *      operationId="getProgramById",
     *      tags={"Program"},
     *      summary="Get program information",
     *      description="Returns program data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Program id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $program = $this->program
            ->newQuery()
            ->with('dishes')
            ->findOrFail($id);
        return $this->sendSuccess($program);
    }

    /**
     * @OA\Delete(
     *      path="/admin/program/{id}",
     *      operationId="deleteProgram",
     *      tags={"Program"},
     *      summary="Delete existing program",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Program id",
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
        $this->program
            ->newQuery()
            ->findOrFail($id)->delete();
        return $this->deleteSuccess();
    }

    public function toggleStatus(Program $program): JsonResponse
    {
        $status = $program->status == ENABLE ? DISABLE : ENABLE;

        $program->update(['status' => $status]);

        return $this->updateSuccess($program);
    }

    /**
     * @OA\Put(
     *      path="/admin/program/{id}",
     *      operationId="updateProgram",
     *      tags={"Program"},
     *      summary="Update existing program",
     *      description="Returns updated program data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Program id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProgramUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       )
     * )
     */
    public function update(ProgramRequest $request, Program $program): JsonResponse
    {
        $data = $request->only([
            'title',
            'banner',
            'description',
            'status',
            'discount_percent',
            'start_date',
            'end_date',
        ]);

        $program->update($data);
        $dishIds = $request->dish_ids;
        $program->dishes()->sync($dishIds);

        return $this->updateSuccess($program);
    }
}