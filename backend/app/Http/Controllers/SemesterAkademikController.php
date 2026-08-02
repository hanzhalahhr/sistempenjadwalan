<?php

namespace App\Http\Controllers;

use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class SemesterAkademikController extends Controller
{

    public function index()
    {
        return response()->json(
            SemesterAkademik::all()
        );
    }

}