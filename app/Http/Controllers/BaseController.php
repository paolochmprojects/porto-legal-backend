<?php

namespace App\Http\Controllers;

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
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="JWT Authorization header using the Bearer scheme. Example: 'Authorization: Bearer {token}'"
 * )
 */
class BaseController extends Controller
{
    //
}
