<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user, protected Room $roomModel)
    {
        $this->user = $user;
    }

    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="authRegister",
     *      tags={"Authenticate"},
     *      summary="Create new",
     *      description="Returns user data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserRegister")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User register success",
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error Internal server"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $user = $this->user->newQuery()->where('phone', $request->phone)->first();
        if ($user) {
            return response()->json([
                'message' => 'Phone number is ready exits'
            ], 500);
        } else {
            $user = $this->user->fill($request->except('password'));
            $user->password = Hash::make($request->password);
            $user->save();

            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie(
                env('AUTH_COOKIE_NAME'),
                $token,
                strtotime("+6 months"),
                '/',
                env('SESSION_DOMAIN'),
                true,
                true,
                '',
                'none',
            );
            $response = [
                'user' => $user,
                'token' => $token,
            ];
            return response($response, 201)->cookie($cookie);
        }
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="authLogin",
     *      tags={"Authenticate"},
     *      summary="User Login",
     *      description="Login into system",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserLogin")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login success",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function login(Request $request)
    {
        $user = $this->user
            ->newQuery()
            ->where('phone', $request->phone)
            ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Phone number or password incorrect !'
            ], 500);
        } else {
//            $token = JWTAuth::fromUser($user);
//            return $this->respondWithToken($token);

            $user->tokens()->delete();
            $token = $user->createToken('token')->plainTextToken;

            $cookie = cookie(
                env('AUTH_COOKIE_NAME'),
                $token,
                strtotime("+6 months"),
                '/',
                env('SESSION_DOMAIN'),
                true,
                true,
                '',
                'none',
            );

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 201)->withCookie($cookie);
        }
    }

    /**
     * @OA\Get(
     *     path="/logout",
     *     tags={"Authenticate"},
     *     summary="Logout",
     *     operationId="authLogout",
     *     description="logout",
     *     @OA\Response(
     *         response=200,
     *         description="logout success",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated."
     *              ),
     *         ),
     *     ),
     * )
     */
    public function logout()
    {
//        $res = new Response();
//        $res->headers->clearCookie(env('AUTH_COOKIE_NAME'));
//        $res->send();
        return response([''], 200)->withCookie(cookie(env('AUTH_COOKIE_NAME'),
            '',
            '-1'
        ));
    }

    /**
     * @OA\Post(
     *      path="/check-user-phone",
     *      operationId="checkUserPhone",
     *      tags={"Authenticate"},
     *      summary="Check User Phone",
     *      description="Check User Phone and return boolean value",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  property="phone"
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login success",
     *           @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="false",
     *                  property="isExits"
     *              )
     *          ),
     *       ),
     * )
     */
    public function checkPhone(Request $request)
    {
        $userExitsInRoom = $this->roomModel->newQuery()
            ->where('user_phone', $request->phone)
            ->first();
        if ($userExitsInRoom) {
            $this->newQuery()
                ->where('id', $userExitsInRoom->id)
                ->update(['user_name', $request->name]);
        }
        $data = ['user_phone' => $request->phone, 'user_name' => $request->name];
        $this->roomModel->newQuery()->create($data);

        $user = $this->user
            ->newQuery()
            ->where('phone', $request->phone)
            ->first();
        if (!$user) {
            return response()->json([
                'isExits' => false
            ], 200);
        } else {
            return response()->json([
                'isExits' => true
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     tags={"Authenticate"},
     *     summary="Get user",
     *     operationId="getProfile",
     *     description="Returns a single user.",
     *     @OA\Response(
     *         response=200,
     *         description="return a user",
     *         @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated."
     *              ),
     *         ),
     *     ),
     * )
     */
    public function profile(): JsonResponse
    {
        return $this->sendSuccess(auth()->user());
    }

    private function getCookie($token)
    {
        return Cookie::create(env('AUTH_COOKIE_NAME'))
            ->withValue($token)
            ->withExpires(strtotime("+6 months"))
            ->withSecure(true)
            ->withHttpOnly(true)
            ->withDomain(env('SESSION_DOMAIN'))
            ->withSameSite("none");
    }


}
