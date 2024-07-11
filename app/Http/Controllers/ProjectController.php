<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Get all projects",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="projects", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                     @OA\Property(property="title", type="string", example="Project 1"),
     *                     @OA\Property(property="description", type="string", example="Description 1"),
     *                     @OA\Property(property="user_id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'projects' => auth()->user()->projects
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Get project by id",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="project", type="object",
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="title", type="string", example="Project 1"),
     *                 @OA\Property(property="description", type="string", example="Description 1"),
     *                 @OA\Property(property="user_id", type="string", example="1"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Project not found."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="message", type="string", example="The project id field must be a valid UUID."),
     *              ),
     *         ),
     *     ),  
     *      
     * ), 
     */
    public function show($projectId)
    {

        $validator = Validator::make(['projectId' => $projectId], [
            'projectId' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = auth()->user()->id;

        $project = Project::where('id', $projectId)->where('user_id', $user_id)->first();

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'project' => $project
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Create new project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Project 1"),
     *              @OA\Property(property="description", type="string", example="Description 1"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                 @OA\Property(property="title", type="string", example="Project 1"),
     *                 @OA\Property(property="description", type="string", example="Description 1"),
     *                 @OA\Property(property="user_id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *             ),
     *         ),
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ), 
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object", 
     *                    @OA\Property(property="title", type="string", example="The title field is required."),
     *                    @OA\Property(property="description", type="string", example="The description field is required."),
     *              ),
     *         ),
     *     ),
     * ),
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:64'],
            'description' => ['string', 'max:255'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only('title', 'description');
        $data['user_id'] = auth()->user()->id;


        $newProject = Project::create($data);

        if (!$newProject) {
            return response()->json([
                'message' => 'Project creation failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $newProject
        ], 201);
    }



    /**
     * @OA\Put(
     *     path="/api/projects/{projectId}",
     *     summary="Update project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Project 1"),
     *              @OA\Property(property="description", type="string", example="Description 1"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="uuid", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                  @OA\Property(property="title", type="string", example="Project 1 updated"),
     *                  @OA\Property(property="description", type="string", example="Description 1 updated"),
     *                  @OA\Property(property="user_id", type="uuid", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),            
     *          ),
     *         ),     
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *      ),
     *      
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Project not found."),
     *          ),
     *      ), 
     *      @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object",
     *                    @OA\Property(property="title", type="string", example="The title field is required."),
     *                    @OA\Property(property="description", type="string", example="The description field is required."),
     *              ),
     *         ),
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Bad request"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="projectId", type="object", 
     *                      @OA\Property(property="string", type="string", example="The project id field must be a valid UUID."),
     *                  ),
     *              ),
     *         ),
     *      ),
     * ),   
     */
    public function update(Request $request, $projectId)
    {

        $validator = Validator::make(['projectId' => $projectId], [
            'projectId' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'errors' => $validator->errors()
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:64'],
            'description' => ['string', 'max:255'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only('title', 'description');

        $userId = auth()->user()->id;

        $project = Project::where('id', $projectId)->where('user_id', $userId)->first();

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $project->update($data);

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }



    /**
     * @OA\Delete(
     *     path="/api/projects/{projectId}",
     *     summary="Delete project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="f8ef2c0-5d4a-11ed-afa1-0242ac120002"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project deleted successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Validation failed"),
     *                  @OA\Property(property="errors", type="object",
     *                        @OA\Property(property="projectId", type="string", example="The project id field must be a valid UUID."),
     *                  ),        
     *          ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Project not found",
     *         @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Project not found"),
     *          ),
     *      ),
     * )
     * 
     */
    function destroy($projectId)
    {

        $validator = Validator::make(['projectId' => $projectId], [
            'projectId' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
