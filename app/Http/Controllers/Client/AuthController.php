<?php

    namespace App\Http\Controllers\client;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\AuthRequest;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        protected $user;

        public function __construct(User $user)
        {
            $this->user = $user;
        }

        public function login(AuthRequest $request)
        {
            $user = $this->user->newQuery()->where('phone', $request->phone)->first();
            if ($user) {
                $user->update(['name'=>$request->name]);
//                $user->createToken('API Token')->plainTextToken;
            } else {
                $user = $this->user->newQuery()->create($request->all());
//                    ->createToken('API Token')->plainTextToken;
            }

            return $this->createSuccess($user);

        }

        public function profile($id)
        {
//            $id=auth()->user()->id;
            $user=$this->user->newQuery()->findOrFail($id);

            return $this->sendSuccess($user);

        }

        public function update(Request $request,$id)
        {
//            $id=auth()->user()->id;
            $user=$this->user->newQuery()->findOrFail($id);
            $user->update($request->all());

            return $this->updateSuccess($user);

        }


    }
