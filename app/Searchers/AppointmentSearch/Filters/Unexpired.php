<?php

namespace App\Searchers\AppointmentSearch\Filters;

use Illuminate\Database\Query\Builder;
use App\Searchers\Filter as Filter;

class Unexpired implements Filter
{

    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {		
		if( $value )
		{
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$current_date = date("Y-m-d H:i:s");
			
			return $builder->where( 'appointment_date', '>', $current_date );
		}
		
		return $builder;
    }
}