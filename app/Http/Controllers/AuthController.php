<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AuthController extends BaseController
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your-jwt-token")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         ),
     *     ),
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

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get user info",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="user", type="object",
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="John"),
     *                  @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *              ),
     *             ),   
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *          ),
     *     ),
     * )
     */
    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/refresh",
     *     summary="Refresh token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."), 
     *         ),
     *     ),
     * )
     */
    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged out successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."), 
     *         ),
     *     ),
     * )
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
