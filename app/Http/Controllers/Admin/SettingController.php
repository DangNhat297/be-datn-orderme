<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingModel;

    function __construct(Setting $settingModel)
    {
        $this->settingModel = $settingModel;
    }

    /**
     * @OA\Get(
     *      path="/admin/setting",
     *      operationId="getSettings",
     *      tags={"Setting"},
     *      summary="Get list of setting",
     *      description="Returns list of setting",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SettingRespone")
     *       ),
     *     )
     */
    public function index(): JsonResponse
    {
        $setting = $this->settingModel->query()->first();
        return $this->sendSuccess($setting);
    }


    /**
     * @OA\Put(
     *      path="/admin/setting/{id}",
     *      operationId="updateSetting",
     *      tags={"Setting"},
     *      summary="Update existing Setting",
     *      description="Returns updated Setting data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Setting id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SettingUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SettingRespone")
     *       )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $setting = $this->settingModel->newQuery()->findOrFail($id);
        $setting->update($request->all());
        return $this->updateSuccess($setting);
    }


}
