<?php
//
//namespace App\Http\Controllers\File;
//
//use App\Http\Controllers\Controller;
//use Illuminate\Http\JsonResponse;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//
//class UploadFileController extends Controller
//{
//    /**
//     * @OA\Post(
//     *      path="/file/upload",
//     *      operationId="uploadFile",
//     *      tags={"Upload"},
//     *      summary="Upload file",
//     *      description="Returns a path file",
//     *      @OA\RequestBody(
//     *          required=true,
//     *          @OA\JsonContent(ref="#/components/schemas/FileUploadRequest")
//     *      ),
//     *      @OA\Response(
//     *          response=201,
//     *          description="Successful operation",
//     *          @OA\JsonContent(ref="#/components/schemas/FileResponse")
//     *       ),
//     * )
//     */
//    public function uploadFileToS3(Request $request): JsonResponse
//    {
//        $path = $request->file('file')->store('images', 's3');
//        Storage::disk('s3')->setVisibility($path, 'public');
//        return $this->sendSuccess($path);
//    }
//}
