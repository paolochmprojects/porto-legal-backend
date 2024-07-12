<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects/{projectId}/tasks",
     *     summary="Get tasks by project id",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="tasks", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                     @OA\Property(property="title", type="string", example="Task 1"),
     *                     @OA\Property(property="description", type="string", example="Description 1"),
     *                     @OA\Property(property="project_id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-11T00:00:00.000000Z"),
     *                 ),
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project not found"),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validated failed"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="projectId", type="array",
     *                     @OA\Items(type="string", example="The project id must be a valid UUID"),
     *                 ),
     *             ),
     *         ),
     *     ),
     * ),
     */
    public function index($projectId)
    {

        $validatorProjectId = Validator::make(['projectId' => $projectId], [
            'projectId' => ['uuid']
        ]);

        if ($validatorProjectId->fails()) {
            return response()->json([
                'message' => 'Validated failed',
                'errors' => $validatorProjectId->errors()
            ], 400);
        }
        
        $userId = auth()->user()->id;
        $userProject = Project::where('id', $projectId)->where('user_id', $userId)->first();

        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $tasks = $userProject->tasks()->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/projects/{projectId}/tasks",
     *     summary="Create task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Task 1"),
     *             @OA\Property(property="description", type="string", example="Description 1"),
     *             @OA\Property(property="status", type="string", example="TODO"),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="task", type="object",
     *                 @OA\Property(property="id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                 @OA\Property(property="title", type="string", example="Task 1"),
     *                 @OA\Property(property="description", type="string", example="Description 1"),
     *                 @OA\Property(property="status", type="string", example="TODO"),
     *                 @OA\Property(property="project_id", type="string", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
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
     *      @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project not found"),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validated failed"),
     *             @OA\Property(property="errors", type="object",
     *                     @OA\Property(property="projectId", type="string", example="The project id must be a valid UUID"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                     @OA\Property(property="title", type="string", example="undefined | The title field is required. | The title must be a string. | The title must be at least 3 characters. | The title may not be greater than 255 characters."),
     *                     @OA\Property(property="description", type="string", example="undefined | The description field is required. | The description must be a string. | The description must be at least 3 characters. | The description may not be greater than 255 characters."),
     *                     @OA\Property(property="status", type="string", example="undefined | The status field is required. | The selected status is invalid."),
     *             ),
     *         ),
     *     ),
     * ),
     */
    public function store(Request $request, $projectId)
    {

        $validatorProjectId = Validator::make(['projectId' => $projectId], [
            'projectId' => ['uuid']
        ]);

        if ($validatorProjectId->fails()) {
            return response()->json([
                'message' => 'Validated failed',
                'errors' => $validatorProjectId->errors()
            ], 400);
        }


        $userProject = Project::where('id', $projectId)->where('user_id', auth()->user()->id)->first();

        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
            'status' => ['required', 'string', 'in:TODO,IN_PROGRESS,DONE,CANCELED'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        $task = $userProject->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ]);


        return response()->json([
            'task' => $task
        ], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/projects/{projectId}/tasks/{taskId}",
     *      summary="Get task",
     *      description="Get task",
     *      operationId="getTask",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="projectId",
     *          description="Project Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="taskId",
     *          description="Task Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="task", type="object",
     *                  @OA\Property(property="id", type="uuid", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                  @OA\Property(property="title", type="string", example="Task 1"),
     *                  @OA\Property(property="description", type="string", example="Description 1"),
     *                  @OA\Property(property="status", type="string", example="TODO"),
     *                  @OA\Property(property="project_id", type="uuid", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                  @OA\Property(property="created_at", type="string", example="2024-07-11T00:00:00.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-07-11T00:00:00.000000Z"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Task not found | Project not found"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     * ),
     */
    public function show($projectId, $taskId)
    {
        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid'],
            'task_id' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->user()->id;

        $userProject = Project::where('id', $projectId)->where('user_id', $userId)->first();

        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $task = $userProject->tasks()->where('id', $taskId)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'task' => $task
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/projects/{projectId}/tasks/{taskId}",
     *      summary="Update task",
     *      description="Update task",
     *      operationId="updateTask",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="projectId",
     *          description="Project Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="taskId",
     *          description="Task Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Task 1"),
     *              @OA\Property(property="description", type="string", example="Description 1"),
     *              @OA\Property(property="status", type="string", example="TODO"),
     *          ),
     * 
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Task updated successfully"),
     *              @OA\Property(property="task", type="object",
     *                  @OA\Property(property="id", type="uuid", example="9c7fc3f6-e7bf-41b7-af14-edc0f1a09d85"),
     *                  @OA\Property(property="title", type="string", example="Task 1"),
     *                  @OA\Property(property="description", type="string", example="Description 1"),
     *                  @OA\Property(property="status", type="string", example="TODO"),
     *                  @OA\Property(property="created_at", type="string", example="2024-07-11T00:00:00.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-07-11T00:00:00.000000Z"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Task not found | Project not found"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     * 
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="projectId", type="string|undefined", example="The project id field must be a UUID"),
     *                  @OA\Property(property="taskId", type="string|undefined", example="The task id field must be a UUID"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unprocessable Entity"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="title", type="string|undefined", example="undefined | The title field is required | The title must be a string | The title must be at least 3 characters. | The title may not be greater than 255 characters"),
     *                  @OA\Property(property="description", type="string|undefined", example="undefined | The description field is required | The description must be a string| The description must be at least 3 characters. | The description may not be greater than 255 characters"),
     *                  @OA\Property(property="status", type="string|undefined", example="undefine | The status field is required | The selected status is invalid"),
     *              ),
     *          ),
     *      ),
     * ),
     */
    public function update(Request $request, $projectId, $taskId)
    {

        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid'],
            'task_id' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth()->user()->id;
        $userProject = Project::where('id', $projectId)->where('user_id', $userId)->first();

        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }


        $task = $userProject->tasks()->where('id', $taskId)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
            'status' => ['required', 'string', 'in:TODO,IN_PROGRESS,DONE,CANCELED'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ]);


        return response()->json([
            'task' => $task
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{projectId}/tasks/{taskId}",
     *     summary="Delete task",
     *     tags={"Tasks"},
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Task deleted successfully"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Task not found | Project not found"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Bad request"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="projectId", type="string" , example="The project id field must be a valid UUID"),
     *                  @OA\Property(property="taskId", type="string", example="The task id field must be a valid UUID"),
     *              ),
     *          ),
     *      ),
     * ),
     */
    public function destroy($projectId, $taskId)
    {

        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid'],
            'task_id' => ['uuid']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'errors' => $validator->errors()
            ], 400);
        }

        $userProject = Project::where('id', $projectId)->where('user_id', auth()->user()->id)->first();

        if (!$userProject) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }


        $task = $userProject->tasks()->where('id', $taskId)->first();

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ], 200);
    }
}
