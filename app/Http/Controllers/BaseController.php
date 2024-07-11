<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="0.0.1",
 *      description="API CRUD desarrollado en laravel con docker, para la gestión de proyectos y tareas de los usuarios authenticados",
 *      @OA\Contact(
 *          name="Paolo Charca Mamani",
 *          email="paolo.dev.projects@gmail.com",
 *          url="https://paolochm.netlify.app"
 *      ),
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
