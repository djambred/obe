<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\View\View;

class LecturerController extends Controller
{
    /**
     * Display the lecturer profile page
     */
    public function show(Lecturer $lecturer): View
    {
        return view('lecturer.profile', compact('lecturer'));
    }
}
