<?php

	Route::group(['prefix'=>'/v1'], function(){
		//http://laravel.win/api/v1/

		Route::post('/register', 'Auth\RegisterController@register');	
		Route::post('/login', 'Auth\LoginController@login');	
		
		//Groups all message that need an logged user ( with a valid api_token) to work
		Route::group(['middleware' => 'auth:api'], function(){	

			Route::post('/logout', 'Auth\LoginController@logout');		
			Route::post('/deactivate', 'Auth\LoginController@deactivate');
		
			include 'user/api.php';
			include 'clinic/api.php';
			include 'hcp/api.php';
			include 'speciality/api.php';
			include 'patient/api.php';
			
			Route::get('/genders', 'GenderController@all');
			Route::get('/appointment_status', 'AppointmentController@all_status');
			Route::post('/appointment/available', 'AppointmentController@search_available');
			Route::post('/appointment/cancel', 'AppointmentController@cancel_appointment');
		});	
	});

?>