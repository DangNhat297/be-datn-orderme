<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/dishes",
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
    public function index(){
        return response()->json([
            'data' => ['aka','dadas']
        ]);
    }
}
