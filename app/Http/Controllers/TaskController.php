<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($projectId)
    {

        $validatorProjectId = Validator::make(['id' => $projectId], [
            'id' => ['uuid', 'exists:projects,id']
        ]);

        if ($validatorProjectId->fails()) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $userProject = Project::where('id', $projectId)->where('user_id', auth()->user()->id)->first();

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $projectId)
    {

        $validatorProjectId = Validator::make(['id' => $projectId], [
            'id' => ['uuid', 'exists:projects,id']
        ]);

        if ($validatorProjectId->fails()) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
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
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($projectId, $taskId)
    {
        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid', 'exists:projects,id'],
            'task_id' => ['uuid', 'exists:tasks,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 404);
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


        return response()->json([
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $projectId, $taskId)
    {

        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid', 'exists:projects,id'],
            'task_id' => ['uuid', 'exists:tasks,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 404);
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


        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
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
     * Remove the specified resource from storage.
     */
    public function destroy($projectId, $taskId)
    {

        $validator = Validator::make([
            'project_id' => $projectId,
            'task_id' => $taskId
        ],
        [
            'project_id' => ['uuid', 'exists:projects,id'],
            'task_id' => ['uuid', 'exists:tasks,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 404);
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
        ]);
    }
}
