<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = UserDetail::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        if ($request->filled('gender')) {
            $search = $request->gender;
            $query->where('gender', '=', $search);
        }
        if ($request->filled('state')) {
            $search = $request->state;
            $query->where('state', '=', $search);
        }
        if ($request->filled('city')) {
            $search = $request->city;
            $query->where('city', '=', $search);
        }

        $sortField = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'asc'); 

        $allowedColumns = ['id', 'name', 'email', 'dob']; 
        if (in_array($sortField, $allowedColumns)) {
            $query->orderBy($sortField, $sortOrder);
        }

        if($request->limit == 'all'){
            return $query->get();
        }
        $perPage = $request->input('limit', $request->limit ?? 10);
        
        return $query->paginate($perPage);
    }

    // GET single user detail
    public function show($id)
    {
        $detail = UserDetail::with('user')->where('user_id', $id)->first();

        if (!$detail) {
            return response()->json([
                'message' => 'User detail not found'
            ], 404);
        }

        return response()->json($detail);
    }

    // INSERT new user detail
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'role' => 'nullable|integer',
            'profile_image' => 'nullable|url|starts_with:https://ybiwqilvsxrnsjboenek.supabase.co'
        ]);
     
        
        $user = DB::transaction(function () use ($validated, $request) {

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Create user detail (user_id auto inserted)
            $user->detail()->create([
                'name' => $validated['name'] ?? '',
                'email' => $validated['email'] ?? '',
                'phone' => $validated['phone'] ?? null,
                'street' => $validated['street'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'dob' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'role' => $validated['role'] ?? null,
                'profile_image' => $validated['profile_image'] ?? null,
            ]);

            return $user->load('detail');
        });

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $userDetail = UserDetail::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userDetail->user_id,
            'phone' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'role' => 'nullable|integer',
            'profile_image' => 'nullable|url|starts_with:https://ybiwqilvsxrnsjboenek.supabase.co'
        ]);

    
        DB::transaction(function () use ($validated, $userDetail, $request) {
            $imagePath = $userDetail->profile_image;
            if ($request->filled('profile_image')) {
                $imagePath = $request->profile_image;
            } 

            // Update user table
            if (isset($validated['name']) || isset($validated['email'])) {
                $userDetail->user->update([
                    'name' => $validated['name'] ?? $userDetail->user->name,
                    'email' => $validated['email'] ?? $userDetail->user->email,
                ]);
            }

            // Update user details table
            $userDetail->update([
                'name' => $validated['name'] ?? $userDetail->name,
                'email' => $validated['email'] ?? $userDetail->email,
                'phone' => $validated['phone'] ?? $userDetail->phone,
                'street' => $validated['street'] ?? $userDetail->street,
                'city' => $validated['city'] ?? $userDetail->city,
                'state' => $validated['state'] ?? $userDetail->state,
                'pincode' => $validated['pincode'] ?? $userDetail->pincode,
                'dob' => $validated['dob'] ?? $userDetail->dob,
                'gender' => $validated['gender'] ?? $userDetail->gender,
                'role' => $validated['role'] ?? $userDetail->role,
                'profile_image' => $imagePath,
            ]);
        });

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $userDetail->load('user')
        ]);
    }

    public function destroy(string $id)
    {
        $userDetail = UserDetail::where('user_id', $id)->first();
        $user = $userDetail->user;

        DB::transaction(function () use ($user) {
            $user->license()->delete(); 

            $user->delete();
        });

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
