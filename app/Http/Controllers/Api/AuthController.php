<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:255',
                'age' => 'nullable|integer',
                'dob' => 'nullable|date',
                'gender' => 'nullable|string|max:255',
                'role' => 'required|integer',
                'profile_image' => 'nullable|image|extensions:jpeg,png,jpg|max:4096'
            ]);
        
            $result = DB::transaction(function () use ($request) {

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                
                $imagePath = null;
                if ($request->hasFile('profile_image')) {
                    $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                }

                $user->detail()->create([
                    'name'   => $request->name,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'age'    => $request->age,
                    'dob'    => $request->dob,
                    'gender' => $request->gender,
                    'role'   => $request->role,
                    'profile_image' => $imagePath
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;
                
                $role = $request->role;

                if ($role == 1) {
                    $permission = [
                        'add-user' => true,
                        'edit-user' => true,
                        'delete-user' => true,
                        'add-tasks' => true,
                        'edit-tasks' => true,
                        'delete-tasks' => true,
                    ];
                } else {
                    $permission = [
                        'add-user' => false,
                        'edit-user' => false,
                        'delete-user' => false,
                        'add-tasks' => false,
                        'edit-tasks' => false,
                        'delete-tasks' => false,
                    ];
                }
                
                  $user = $user->load('detail');

                    // Add permission inside user object
                    $user->permission = $permission;
                
                return [
                    'access_token' => $token,
                    'user' => $user,
                ];
            });

            return response()->json([
                'access_token' => $result['access_token'],
                'token_type' => 'Bearer',
                'user' => $result['user']
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::with('detail')
            ->where('email', $request['email'])
            ->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $role = $user->detail?->role;

        if ($role === 1) {
            $permission = [
                'add-user' => true,
                'edit-user' => true,
                'delete-user' => true,
                'add-tasks' => true,
                'edit-tasks' => true,
                'delete-tasks' => true,
            ];
        } else {
            $permission = [
                'add-user' => false,
                'edit-user' => false,
                'delete-user' => false,
                'add-tasks' => false,
                'edit-tasks' => false,
                'delete-tasks' => false,
            ];
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'permission' => $permission
            ]
        ]);
    }

    // Password Reset
    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Upsert into password_reset_tokens
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => now()
            ]
        );

       Mail::to($request->email)->send(new \App\Mail\ResetOtpMail($otp));

        return response()->json([
            'message' => 'An OTP has been sent to your email address.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $resetRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (\Carbon\Carbon::parse($resetRecord->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify OTP again just to be secure
        $resetRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Update the user's password
        $user = \App\Models\User::where('email', $request->email)->first();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Delete the token so it cannot be reused
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Password has been reset successfully. You can now login.'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
