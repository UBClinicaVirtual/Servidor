<?php
	//Group for all the clinic related messages		
	Route::group(['prefix'=>'/hcp'], function(){
		//http://laravel.win/api/v1/clinic
		Route::post('/search', 'HCPController@search');
	});
?>