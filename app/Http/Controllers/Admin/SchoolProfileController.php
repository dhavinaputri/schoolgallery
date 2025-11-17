<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    public function edit()
    {
        $profile = SchoolProfile::getProfile();
        return view('admin.school-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'operational_hours' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'map_embed' => 'nullable|string',
        ]);

        $profile = SchoolProfile::first();
        
        $data = $request->except(['school_logo', 'school_name']);

        if ($profile) {
            $profile->update($data);
        } else {
            SchoolProfile::create($data);
        }

        return redirect()->back()->with('success', 'School profile updated successfully.');
    }
}