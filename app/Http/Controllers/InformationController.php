<?php

namespace App\Http\Controllers;

use App\Models\Information;

class InformationController extends Controller
{
    /**
     * Display the specified information page by slug.
     */
    public function show(Information $information)
    {
        return view('frontend.information.show', compact('information'));
    }
}
