<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Program;

class ProgramController extends Controller
{
    public function __construct(protected Program $program)
    {
    }

    /**
     * @OA\Get(
     *      path="/client/programs",
     *      operationId="getProgramsClient",
     *      tags={"Program Client"},
     *      summary="Get list of program",
     *      description="Returns list of program",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       ),
     *     )
     */
    public function index()
    {
        $programs = $this->program
            ->newQuery()
            ->with('dishes')
            ->where('status', ENABLE)
            ->where('end_date', '>=', now())
            ->get();

        $programs->each(function ($program) {
            $program->dishes->transform(function ($dish) {
                $dish->makeHidden([
                    'pivot',
                    'updated_at',
                    'created_at'
                ]);
                
                return $dish;
            });
        });

        return $this->sendSuccess($programs);
    }

    /**
     * @OA\Get(
     *      path="/client/program",
     *      operationId="getProgramShow",
     *      tags={"Program Client"},
     *      summary="Get program information",
     *      description="Returns program data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProgramResponse")
     *       ),
     * )
     */
    public function show()
    {
        $program = $this->program
            ->newQuery()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', ENABLE)
            ->firstOrFail()
            ->load('dishes');

        $program->dishes->transform(function ($dish) {
            $dish->makeHidden([
                'pivot',
                'updated_at',
                'created_at'
            ]);

            return $dish;
        });

        return $this->sendSuccess($program);
    }
}
