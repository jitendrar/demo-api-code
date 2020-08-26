<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
	
	public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $status = 0;
        $msg = "The credential that you've entered doesn't match any account.";
        $accessToken = "";
        $data = [];

        $data = array();

        $loginData = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        // check validations
        if ($loginData->fails()) {
            $messages = $loginData->messages();

            $status = 0;
            $msg = "";

            foreach ($messages->all() as $message) {
                $msg = $message;
                break;
            }
        }
        else
        {
            $loginData = [
                'email' => $request->get("email"),
                'password' => $request->get("password"),
            ];

            if (auth()->attempt($loginData)) {
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                $status = 1;
                $msg = "Login Successfully.";   
                $user = auth()->user();
                $data = new UserResource($user);
            }
        }        

        return ['status' => $status, 'message' => $msg, 'data' => $data,'access_token' => $accessToken];
    }
}
