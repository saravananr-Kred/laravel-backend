<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start the query
        $query = User::query();

        // Only apply filter if 'search' has a value
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Return the result as JSON
        return response()->json($query->get());
    }


    // Fetch tasks belong with users
    public function getUserTasks($userId) 
    { 
        $user = \App\Models\User::with('tasks')->findOrFail($userId); 
        return response()->json([ 'data' => $user->tasks ]); 
    }

    // Fetch tasks belong with users
    public function searchUserAndTasks(Request $request) 
    { 
        $search = $request->search;
        $user = \App\Models\User::where('name', 'LIKE', '%' . $search . '%')->limit(5)->get()->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'type' => 'user'
        ]); 
        $task = \App\Models\Task::where('name', 'LIKE', '%' . $search . '%')->limit(5)->get()->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'type' => 'task'
        ]); 
        return response()->json($user->merge($task)); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

        $user = \App\Models\User::create($validated);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return response()->json(null, 201);
    }
}
