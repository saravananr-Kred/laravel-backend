<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\License;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(License::all());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        try{
            $validated = $request->validate([
                'licenses' => 'required|array',
                'licenses.*.id' => 'required|integer',
                'licenses.*.number' => 'required|string',
                'licenses.*.startDate' => 'required|string',
                'licenses.*.endDate' => 'required|string',
                'licenses.*.city' => 'required|string',
                'licenses.*.state' => 'required|string',
            ]);

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $licenses = [];

            foreach ($validated['licenses'] as $licenseData) {

                // UPDATE
                if ($licenseData['id'] != 0) {

                    $license = License::find($licenseData['id']);

                    if ($license) {
                        $license->update([
                            'license_number' => $licenseData['number'],
                            'license_start_date' => $licenseData['startDate'],
                            'license_end_date' => $licenseData['endDate'],
                            'license_city' => $licenseData['city'],
                            'license_state' => $licenseData['state'],
                        ]);
                    }

                } 
                // CREATE
                else {

                    $license = License::create([
                        'user_id' => $id,
                        'license_number' => $licenseData['number'],
                        'license_start_date' => $licenseData['startDate'],
                        'license_end_date' => $licenseData['endDate'],
                        'license_city' => $licenseData['city'],
                        'license_state' => $licenseData['state'],
                    ]);

                }

                $licenses[] = $license;
            }

            return response()->json([
                'message' => 'success',
                'data' => $licenses
            ], 201);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add license', 'message' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validate = User::find($id);
        if (!$validate) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $license = License::where('user_id', $id)->get();
        return response()->json($license);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uid, string $id)
    {
        $validate = User::where('user_id', $uid)->first();
        if (!$validate) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $validated = $request->validate([
            'number' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'city' => 'required|string',
            'state' => 'required|string',
        ]);
        $license = License::findOrFail($id);
        $license->update([
            'license_number' => $validated['number'],
            'license_start_date' => $validated['start_date'],
            'license_end_date' => $validated['end_date'],
            'license_city' => $validated['city'],
            'license_state' => $validated['state'],
        ]);
        return response()->json([
            'message' => 'success',
            'data' => $license
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $license = License::findOrFail($id);
        $license->delete();
        return response()->json([
            'message' => 'success',
            'data' => $license
        ]);
    }
}
