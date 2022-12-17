<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(protected Program $program,protected Order $orders) {
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

        $program->load('dishes');

        $program->dishes->transform(function ($dish) {
            $dish->makeHidden([
                'pivot',
                'created_at',
                'updated_at'
            ]);

            return $dish;
        });

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
            ->findOrFail($id);

        $orders = $this->orders
            ->newQuery()
            ->whereBetween('created_at', [$program->start_date, $program->end_date])
            ->with('dishes')
            ->get();

        $dishes = $orders->reduce(fn ($init, $order) => $init->merge($order->dishes), collect([]))
            ->transform(fn($p) => $p->makeHidden(['pivot', 'created_at', 'updated_at', 'slug', "description", "content", 'quantity', 'category_id', 'status']))
            ->unique('id')
            ->values();

        $dishes->transform(function ($product) use ($orders) {
            $product->quantity_buy = $orders->reduce(function ($init, $order) use ($product) {
                return $init += $order->dishes
                    ->where('id', $product->id)
                    ->sum(fn($d) => $d->pivot->quantity);
            }, 0);
            $product->total = $product->quantity_buy * ($product->price - $product->pivot->price_sale);
            return $product;
        }, collect([]));

        return response()->json(
            [
                'data'=>$program,
                'dishes_flashSale'=>$dishes,
                'total_flashSale'=>$dishes->sum('total')
            ]
        );
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
        $program = $this->program
            ->newQuery()
            ->where('id', $id)
            ->firstOrFail();

        $program->update(['status' => DISABLE]);

        return $this->deleteSuccess();
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

        $program->load('dishes');

        $program->dishes->transform(function ($dish) {
            $dish->makeHidden([
                'pivot',
                'created_at',
                'updated_at'
            ]);

            return $dish;
        });

        return $this->updateSuccess($program);
    }
}
