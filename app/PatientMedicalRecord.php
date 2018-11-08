<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientMedicalRecord extends Model
{
    protected $fillable=['appointment_id', 'description',];
}
