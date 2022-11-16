<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 'fail',
                'validation_errors' => $validator->errors(),
            ]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if($user)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'User registration successfully completed!',
                'data' => $user,
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'User registration failed!',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 'fails',
                'validation_errors' => $validator->errors(),
            ]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            $token = $user->createToken('usertoken')->accessToken;

            return response()->json([
                'status' => 'success',
                'login' => true,
                'token' => $token,
                'data' => $user,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid email or password!',
            ]);
        }
    }

    public function userDetails()
    {
        $user = Auth::user();
        if($user)
        {
            return response()->json([
                'status' => 'success',
                'user' => $user,
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'User not found!',
        ]);
    }
}
