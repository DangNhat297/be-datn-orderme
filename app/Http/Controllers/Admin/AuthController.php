<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\AuthRequest;
    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class AuthController extends Controller
    {
        protected $user;

        public function __construct(User $user)
        {
            $this->user = $user;

        }

        function register(Request $request):JsonResponse
        {
            $validate=Validator::make($request->all(),[
                'name'=>'required',
                'phone'=>'required|unique:users|numeric|min:11',
                'password'=>'required|confirmed|min:6',
                'password_confirmation'=>'required_with:password|same:password|min:6'
            ]);
            if ($validate->fails()){
                return response()->json([
                    'result'=>false,
                    'message'=>$validate->errors()->all()
                ]);
            }
            $user = $this->user->fill($request->except('password'));
            $user->password=Hash::make($request->password);
            $user->save();
            return  $this->createSuccess($user);
        }



        function login(Request $request):JsonResponse
        {
            $user = $this->user->newQuery()
                ->where('phone',$request->phone)
                ->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                  return $this->loginSuccess($user);
                }else{
                    return response()->json([
                            'message'=>'Incorrect password'
                    ]);
                }
            }else{
                return response()->json([
                    'message'=>'User not Found'
                ]);
            }
        }


        public function profile():JsonResponse
        {
            $user = $this->user->newQuery()->findOrFail(auth()->id());
            return $this->sendSuccess($user);
        }



        public function update(Request $request):JsonResponse
        {
            $user = $this->user->newQuery()->findOrFail(auth()->id());
            $user->fill($request->except('password'));
            if(!empty($request->password_old)){
                if(Hash::check($request->password_old,$user->password)){
                    $user->password=Hash::make($request->password);
                }
            }
            $user->save();
            return $this->updateSuccess($user);
        }



        protected function loginSuccess($user):JsonResponse
        {
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'result' => true,
                'message' => 'Successfully logged in',
                'data' => [
                    'access_token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                    ]
                ]
            ]);
        }
    }
