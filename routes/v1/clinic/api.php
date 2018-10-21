<?php
	//Group for all the clinic related messages		
	Route::group(['prefix'=>'/clinic'], function(){
		//http://laravel.win/api/v1/clinic
		Route::post('/search', 'ClinicController@search');			
	});
?>