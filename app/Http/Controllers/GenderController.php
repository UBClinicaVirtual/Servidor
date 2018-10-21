<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gender;

class GenderController extends Controller
{
    public function all(Request $request)
	{
		return response()->json(['genders' => Gender::all() ], 200);
	}
}
