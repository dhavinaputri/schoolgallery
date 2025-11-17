<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = StorageHelper::storeFile($request->file('image'), 'teachers');
        }

        $teacher = Teacher::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully',
            'data' => $teacher
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($teacher->image) {
                StorageHelper::deleteFile($teacher->image);
            }
            
            $validated['image'] = StorageHelper::storeFile($request->file('image'), 'teachers');
        }

        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully',
            'data' => $teacher
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Delete image if exists
        if ($teacher->image) {
            Storage::delete('public/' . $teacher->image);
        }
        
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully'
        ]);
    }
}
