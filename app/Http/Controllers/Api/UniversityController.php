<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Heroes\University;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class UniversityController extends Controller
{
    public function getFaculties(University $university)
    {
        return response()->json($university->faculties);
    }
}
