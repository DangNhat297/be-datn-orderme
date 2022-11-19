<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Order Me API"
 * )
 * @OA\SecurityScheme(
 *      type="http",
 *      description="Login with email and password to get the authentication token",
 *      name="Token based Based",
 *      in="header",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="tokenJWT",
 *)
 * @OA\Tag(
 *     name="User Authenticate",
 *     description="Đăng nhập trước nhé <3"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * createSuccess
     *
     * @param mixed $data
     * @param mixed $message
     * @return JsonResponse
     */
    public function createSuccess($data): JsonResponse
    {
        return $this->sendSuccess($data, 201);
    }

    /**
     * sendSuccess
     *
     * @param mixed $data
     * @param mixed $code
     * @param mixed $message
     * @return JsonResponse
     */
    public function sendSuccess($data, $code = 200, $message = 'Successfully'): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $code);
    }

    /**
     * updateSuccess
     *
     * @param mixed $data
     * @param mixed $message
     * @return JsonResponse
     */
    public function updateSuccess($data): JsonResponse
    {
        return $this->sendSuccess($data, 201);
    }

    /**
     * deleteSuccess
     *
     * @return JsonResponse
     */
    public function deleteSuccess(): JsonResponse
    {
        return response()->json([], 204);
    }
}
