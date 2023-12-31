<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Validator;

class AuthController extends Controller
{
    /**
    * Create a new AuthController instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public $token = true;
    public function register(Request $request)
    {

         $validator = Validator::make($request->all(),
                      [
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',
                     ]);

         if ($validator->fails()) {

               return response()->json(['error'=>$validator->errors()], 401);

            }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->token) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => "registered"
        ], Response::HTTP_OK);
    }


        /**
        * Get a JWT via given credentials.
        *
        * @return \Illuminate\Http\JsonResponse
        */

        public function login()
        {
            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        }

        /**
        * Get the authenticated User.
        *
        * @return \Illuminate\Http\JsonResponse
        */
        public function me()
        {
            return response()->json(auth()->user());
        }

        /**
        * Log the user out (Invalidate the token).
        *
        * @return \Illuminate\Http\JsonResponse
        */
        public function logout()
        {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }

        /**
        * Refresh a token.
        *
        * @return \Illuminate\Http\JsonResponse
        */
        public function refresh()
        {
            return $this->respondWithToken(auth()->refresh());
        }

        /**
        * Get the token array structure.
        *
        * @param  string $token
        *
        * @return \Illuminate\Http\JsonResponse
        */
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }
    }
