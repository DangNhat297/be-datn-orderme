<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * sendSuccess
     *
     * @param  mixed $data
     * @param  mixed $code
     * @param  mixed $message
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
     * createSuccess
     *
     * @param  mixed $data
     * @param  mixed $message
     * @return JsonResponse
     */
    public function createSuccess($data, $message): JsonResponse
    {
        return $this->sendSuccess($data, 201, $message);
    }
    
    /**
     * updateSuccess
     *
     * @param  mixed $data
     * @param  mixed $message
     * @return JsonResponse
     */
    public function updateSuccess($data, $message): JsonResponse
    {
        return $this->sendSuccess($data, 201, $message);
    }
    
    /**
     * deleteSuccess
     *
     * @return JsonResponse
     */
    public function deleteSuccess(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
