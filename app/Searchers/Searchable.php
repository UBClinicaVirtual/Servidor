<?php

namespace App\Searchers;

interface Searchable
{
	public static function new_query();
	public static function filter_folder();
} 