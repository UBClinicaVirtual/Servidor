<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'identification_number', 'gender_id', 'user_id', 'birth_date', 'address', 'phone', 
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
	
}
