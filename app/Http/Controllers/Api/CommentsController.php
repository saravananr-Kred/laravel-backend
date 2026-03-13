<?php

namespace App\Http\Controllers\Api;

use App\Models\comments;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tid)
    {
        try{

            $comment = comments::where('task_id', $tid)->get();
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($comment);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(string $tid)
    {
      try{

            $comment = comments::where('task_id', $tid)->get();
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($comment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'task_id' => 'required|exists:tasks,id',
                'user_id' => 'required|exists:users,id',
                'comment' => 'required|string',
            ]);

            if($validated['user_id']){
                $user = User::find($validated['user_id']);
                $validated['user_name'] = $user ? $user->name : null;
            }

            $comment = comments::create($validated);
        
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string',
        ]);

        $comment = comments::where('task_id', $id)->update($validated);

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = comments::where('task_id', $id)->delete();

        return response()->json($comment);
    }
}
