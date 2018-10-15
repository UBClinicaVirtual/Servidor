<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
Groups all the message under the version of the api (v1)
*/

Route::group(['prefix'=>'/v1'], function(){
	//http://laravel.win/api/v1/

	Route::post('/register', 'Auth\RegisterController@register');	
	Route::post('/login', 'Auth\LoginController@login');	
	
	//Groups all message that need an logged user ( with a valid api_token) to work
	Route::group(['middleware' => 'auth:api'], function(){	

		Route::post('/logout', 'Auth\LoginController@logout');		
		Route::post('/deactivate', 'Auth\LoginController@deactivate');
	
		Route::get('/user', function(Request $request){
			return response()->json( Auth::guard('api')->user(), 201);
		});
		
		Route::group(['prefix'=>'/user'], function(){		
			Route::group(['prefix'=>'/clinic'], function(){
				Route::get('', 'ClinicController@get_profile');
				Route::post('', 'ClinicController@update_profile');
				
				Route::post('/appointments', 'ClinicController@search_appointments');
				Route::group(['prefix'=>'/hcpspecialities'], function(){
					Route::post('', 'ClinicController@add_hcpspecialities');
					Route::post('/search', 'ClinicController@search_hcpspecialities');
				});				
				Route::group(['prefix'=>'/schedules'], function(){
					Route::post('', 'ClinicController@add_schedules');
					Route::post('/search', 'ClinicController@search_schedules');
				});
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
		
		//Group for all the clinic related messages		
		Route::group(['prefix'=>'/clinic'], function(){
			//http://laravel.win/api/v1/clinic
			Route::post('/search', 'ClinicController@search');			
		});
		
		//Group for all the clinic related messages		
		Route::group(['prefix'=>'/hcp'], function(){
			//http://laravel.win/api/v1/clinic
			Route::post('/search', 'HCPController@search');
		});
		
		//Group for all the patient related messages		
		Route::group(['prefix'=>'/patient'], function(){
			//http://laravel.win/api/v1/patient			
			Route::post('/search', 'PatientController@search');			
		});
				
		//Group for all the speciality related messages		
		Route::post('/specialities', 'SpecialityController@search');
		
		Route::group(['prefix'=>'/speciality'], function(){	
			//http://laravel.win/api/v1/speciality
			
			Route::post('', 'SpecialityController@create');					
			Route::get('/{speciality}', 'SpecialityController@speciality');
			Route::post('/{speciality}', 'SpecialityController@update')->where('speciality', '[0-9]+');
			
		});
	});	
});
