<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {

        return response()->json([
            'projects' => auth()->user()->projects
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:64'],
            'description' => ['string', 'max:255'],
            'user_id' => ['uuid', 'exists:users,id'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only('title', 'description', 'user_id');

        if (!array_key_exists('user_id', $data)) {
            $data['user_id'] = auth()->user()->id;
        }

        $newProject = Project::create($data);

        if (!$newProject) {
            return response()->json([
                'message' => 'Project creation failed'
            ], 500);
        }

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $newProject
        ]);
    }

    function destroy($id){

        $validator = Validator::make(['id' => $id], [
            'id' => ['uuid', 'exists:projects,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::find($id);
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
