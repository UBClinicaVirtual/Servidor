<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicHCPSpeciality extends Model
{
	protected $fillable = ['hcp_speciality_id'];
	protected $hidden = ['pivot'];
    protected $table ='clinic_hcp_specialities';
}
