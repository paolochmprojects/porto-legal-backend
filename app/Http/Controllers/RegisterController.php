<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="User registration",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string"), example="The name field is required."),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string"), example="The email field is required."),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string"), example="The password field is required."),
     *             ),
     *         ),
     *     ),
     * 
     *      @OA\Response(
     *         response=500,
     *         description="User creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User creation failed"),
     *         ),
     *     ),
     * )
     */
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
        ], 201);
    }
}
