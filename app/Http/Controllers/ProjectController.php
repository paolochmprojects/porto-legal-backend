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

    public function show($id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => ['uuid', 'exists:projects,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = auth()->user()->id;

        $project = Project::where('id', $id)->where('user_id', $user_id)->first();

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'project' => $project
        ]);
    }

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
        ]);
    }


    public function update(Request $request, $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => ['uuid', 'exists:projects,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['string', 'max:64'],
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

        $project = Project::find($id);

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
