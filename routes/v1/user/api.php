<?php
	Route::get('/user', function(Request $request){
		return response()->json( Auth::guard('api')->user()->get_profile(), 201);
	});

	Route::group(['prefix'=>'/user'], function(){		
		Route::group(['prefix'=>'/clinic'], function(){
			Route::get('', 'ClinicController@get_profile');
			Route::post('', 'ClinicController@update_profile');
			
			Route::post('/appointments', 'ClinicController@search_appointments');
			Route::group(['prefix'=>'/hcpspecialities'], function(){
				Route::post('', 'ClinicController@add_hcp_specialities');
				Route::post('/search', 'ClinicController@search_hcp_specialities');
			});				
			Route::group(['prefix'=>'/schedules'], function(){
				Route::post('', 'ClinicController@add_schedules');
				Route::post('/search', 'ClinicController@search_schedules');
			});
			
			Route::post('/appointments', 'ClinicController@search_appointments');
		});
		
		Route::group(['prefix'=>'/patient'], function(){
			Route::get('', 'PatientController@get_profile');
			Route::post('', 'PatientController@update_profile');
			
			Route::post('/appointments', 'PatientController@search_appointments');
		});
		
		Route::group(['prefix'=>'/hcp'], function(){
			Route::get('', 'HCPController@get_profile');
			Route::post('', 'HCPController@update_profile');
			
			Route::post('/appointments', 'HCPController@search_appointments');
		});
	});
?>