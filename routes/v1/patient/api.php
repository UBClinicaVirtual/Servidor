<?php
	//Group for all the patient related messages		
	Route::group(['prefix'=>'/patient'], function(){
		//http://laravel.win/api/v1/patient			
		Route::post('/search', 'PatientController@search');			
	});
?>