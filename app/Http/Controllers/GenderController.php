<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Managers\GenderManager;

class GenderController extends Controller
{
	protected manager()
	{
		return new GenderManager();
	}
	
    public function all(Request $request)
	{
		return $this->manager()->all( $request->all() );
	}
}
