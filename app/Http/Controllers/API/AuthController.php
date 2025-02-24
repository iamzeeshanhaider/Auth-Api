<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
// use Dotenv\Validator;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {

            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];

            return response()->json($response, 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User Register Successfull'
        ];
        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::User();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User Login  Successfull'

            ];
        } else {
            $response = [
                'success' => false,

                'message' => 'User Login Fail'

            ];
        }
        return response()->json($response);
    }
}
