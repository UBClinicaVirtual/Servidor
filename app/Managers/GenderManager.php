<?php

namespace App\Managers;

use App\Gender;

class GenderManager
{
    public function all(array $request)
	{
		return response()->json(['genders' => Gender::all() ], 200);
	}
}
