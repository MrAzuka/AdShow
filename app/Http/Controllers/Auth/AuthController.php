<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Register user
    public function register(Request $request){
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        
        if($validate -> fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->passsword),
                'role' => 'customer'
            ]);

            $userDetails['user'] = $user;

            return $this->sendSuccessResponse($userDetails,'Account Created Successfully', 201);

        } catch (\Throwable $e) {
            return $this->sendErrorResponse('Error:'. $e->getMessage(), $e->getMessage(), 500);
        }
    }

    public function login(Request $request){
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendErrorResponse('Validation failed.', $validate->errors()->first(), 422);
        }

        try {
            
            $user = User::where('email', $request->email)->first();
            $hashed_password = Hash::check($request->password, $user->password);
            if (!$user || $hashed_password) {
                return $this->sendErrorResponse('Incorrect Email or Password.', 'Invalid credentials.', 401);
            }
            
            $userDetails['user'] = $user;
            $userDetails['token'] = $user->createToken('authToken')->plainTextToken;

            return $this->sendSuccessResponse($userDetails,'Logged in Successfully', 201);

        } catch (\Throwable $e) {

            return $this->sendErrorResponse('Incorrect Email Or Password.', $e->getMessage(), 500);
        }
    }
}
