<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::with('assignedTo')->get()->map(function ($task) {
            if($task->file_url) {
                $task->file_url = explode(",",$task->file_url);
                $task->fileCount = count($task->file_url);
            } else {
                $task->file_url = [];
                $task->fileCount = 0;
            }
            return $task;
        });
        return response()->json($tasks);
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        // findOrFail will automatically return a 404 error if the ID doesn't exist
        $task = Task::with('assignedTo')->findOrFail($id);

        if($task->file_url) {
            $task->file_url = explode(",",$task->file_url);
            $task->fileCount = count($task->file_url);
        } else {
            $task->file_url = [];
            $task->fileCount = 0;
        }

        return response()->json($task);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:On Hold,In Progress,Completed,Open',
        ]);
        $task = Task::findOrFail($request->id);
        $task->status = $request->status;
        $task->save();

        return response()->json([
            'message' => 'Task status updated successfully',
            'data' => $task->load('assignedTo'),
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        try {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'sometimes|string|in:On Hold,In Progress,Completed,Open',
            'priority' => 'sometimes|string|in:Low,Medium,High,Very High',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'end_date' => 'nullable|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|extensions:pdf,doc,docx,jpg,png,xlsx|max:5120',
        ]);

        if (!empty($validated['assigned_to'])) {
            $user = User::find($validated['assigned_to']);
            $validated['assigned_to_user_name'] = $user ? $user->name : null;
        }

        if($request->hasFile('documents')) {
            $fileUrls = [];
            $destinationPath = public_path('task_documents');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            foreach ($request->file('documents') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
                $file->move($destinationPath, $fileName);
            
                $fileUrls[] = 'task_documents/' . $fileName;
            }

            $commaSeperatedFileUrls = implode(",",$fileUrls);
            $validated['file_url'] = $commaSeperatedFileUrls;
        }

            $task = Task::create($validated);
            return response()->json([
                'message' => 'Task created successfully',
                'data' => $task->load('assignedTo'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified task.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        
        $tasks = Task::with('assignedTo')
                    ->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->get();

        return response()->json($tasks);
    }
    /**
     * Update the specified task.
     */
    public function update(Request $request, string $id)
    {
        try{

        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|string|in:On Hold,In Progress,Completed,Open',
            'priority' => 'sometimes|string|in:Low,Medium,High,Very High',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'end_date' => 'nullable|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|extensions:pdf,doc,docx,jpg,png,xlsx|max:5120',
        ]);

        if($request->hasFile('documents')) {
            $fileUrls = [];
            $oldFileUrls = explode(",",$task->file_url);
            foreach ($oldFileUrls as $oldFileUrl) {
                if (file_exists(public_path('task_documents/' . $oldFileUrl))) {
                    unlink(public_path('task_documents/' . $oldFileUrl));
                }
            }
            foreach ($request->file('documents') as $file) {
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('task_documents'), $fileName);
                $fileUrls[] = $fileName;
            }

            $commaSeperatedFileUrls = implode(",",$fileUrls);
            $validated['file_url'] = $commaSeperatedFileUrls;
        }

        if (array_key_exists('assigned_to', $validated)) {
            if (!empty($validated['assigned_to'])) {
                $user = User::find($validated['assigned_to']);
                $validated['assigned_to_user_name'] = $user ? $user->name : null;
            } else {
                $validated['assigned_to_user_name'] = null;
            }
        }

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task->load('assignedTo'),
        ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add tasks', 'message' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}
