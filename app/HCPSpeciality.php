<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HCPSpeciality extends Model
{
	protected $hidden = ['pivot'];
    protected $table = 'HCPSpecialities';
}
