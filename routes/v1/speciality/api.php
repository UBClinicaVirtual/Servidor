<?php

	//Group for all the speciality related messages		
	Route::post('/specialities', 'SpecialityController@search');
	
	Route::group(['prefix'=>'/speciality'], function(){	
		//http://laravel.win/api/v1/speciality
		
		Route::post('', 'SpecialityController@create');					
		Route::get('/{speciality}', 'SpecialityController@speciality');
		Route::post('/{speciality}', 'SpecialityController@update')->where('speciality', '[0-9]+');
		
	});
?>