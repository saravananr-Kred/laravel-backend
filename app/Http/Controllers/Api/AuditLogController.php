<?php

namespace App\Http\Controllers\Api;

use App\Models\audit_log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return audit_log::with('user')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'ip_address' => 'required|string|max:255',
        ]);

        $audit_log = audit_log::create($validated);

        return response()->json([
            'message' => 'Audit log created successfully',
            'data' => $audit_log
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(audit_log $audit_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(audit_log $audit_log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, audit_log $audit_log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(audit_log $audit_log)
    {
        //
    }
}
