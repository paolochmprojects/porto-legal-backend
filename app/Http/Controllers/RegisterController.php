<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request)
    {

        // validate data from request
        $validator =  Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        };

        $data = $request->only('name', 'email', 'password');

        $newUser = User::create($data);

        if (!$newUser) {
            return response()->json([
                'message' => 'User creation failed'
            ], 500);
        }

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $newUser     
        ]);
    }
}
