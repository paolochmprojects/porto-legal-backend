<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API CRUD Students"
 * )
 * 
 * @OA\Server(
 *      url="http://localhost:8000",
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Auth"},
     *      summary="Login",
     *      description="Login",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )          
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = auth()->attempt($validated);

        if (!$token) {
            return response()->json(
                [
                    'message' => 'Invalid credentials'
                ],
                401
            );
        }

        return response()->json(
            [
                'token' => $token
            ],
            200
        );
    }

    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
