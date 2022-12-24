<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(protected User $userModel)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/user",
     *      operationId="getUsers",
     *      tags={"User"},
     *      summary="Get list of user",
     *      description="Returns list of user",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="Search by name, phone, email",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          description="User status",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="role",
     *          description="User role",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="orderBy",
     *          description=" sort by query vd :-id,+id,+name,-name,-price,+price",
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
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *       ),
     *     )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->userModel
            ->newQuery()
            ->where('id', '!=', auth()->id())
            ->findByName($request)
            ->findByStatus($request)
            ->findByRole($request)
            ->findOrderBy($request)
            ->paginate($request->limit ?? PAGE_SIZE_DEFAULT);

        $data->getCollection()->transform(function ($value) {
            $value->makeHidden(['password']);
            return $value;
        });

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *      path="/admin/user/{id}",
     *      operationId="updateUser",
     *      tags={"User"},
     *      summary="Update existing User",
     *      description="Returns updated User data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *       )
     * )
     */
    public function update(Request $request, User $user)
    {
        $listRole = [ROLE_CHIEF, ROLE_SHIPPER, ROLE_USER];
        $role = $request->role;
        if ($role) {
            if (in_array($role, $listRole)) {
                $user->update(['role' => $role, 'status' => $request->status]);

                return response()->json($user, 202);
            }
            return response()->json([
                'message' => 'You can update role to chief, shipper and user'
            ], 500);
        }
        $user->update(['status' => $request->status]);
        return response()->json($user, 202);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
