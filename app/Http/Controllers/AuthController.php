<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="authRegister",
     *      tags={"User Authenticate"},
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
    public function register(AuthRequest $request): JsonResponse
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
            return $this->createSuccess($user);
        }
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="authLogin",
     *      tags={"User Authenticate"},
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

            // Delete old tokens
            $user->tokens()->delete();

            // Create new tokens
            $token = $user->createToken('token')->plainTextToken;

            // Create Cookie
            $cookie = Cookie::create(env('AUTH_COOKIE_NAME'))
                ->withValue($token)
                ->withExpires(strtotime("+1 hour"))
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withDomain(env('SESSION_DOMAIN'))
                ->withSameSite("none");

            $response = [
                'user' => $user,
                'token' => $token,
            ];


            // Return user, token and set refresh cookie
            return response($response, 201)->cookie($cookie);
        }
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     tags={"User Authenticate"},
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

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"User Authenticate"},
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
    public function logout(Request $request)
    {
        auth()->logout();

        return [
            'message' => 'Logged out'
        ];
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600
//            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
