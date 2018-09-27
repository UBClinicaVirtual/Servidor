<?php

namespace App\ClinicSearch\Filters;

use Illuminate\Database\Eloquent\Builder;

/*
* Source: https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db
*/

interface Filter
{
    /**
     * Apply a given search value to the builder instance.
     * 
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value);
}