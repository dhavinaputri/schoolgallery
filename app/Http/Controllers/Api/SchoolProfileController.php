<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $profile = SchoolProfile::getProfile();

        return response()->json([
            'success' => true,
            'data' => $profile,
        ]);
    }
}