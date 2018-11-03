<?phpnamespace App\Searchers\ClinicSearch;use App\Clinic;use Illuminate\Http\Request;use Illuminate\Database\Query\Builder;use Illuminate\Support\Facades\DB;/** Source: https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db*/class ClinicSearch implements \App\Searchers\Searchable{    /*    |--------------------------------------------------------------------------    | Clinic Search    |--------------------------------------------------------------------------    |    | This class builds the query to fetch the clinics based on the filters sent on	| the json request. It dynamics load the filter class from the \Filters\ folder    |    */ 		use \App\Searchers\SearchableTrait;	public static function new_query()	{		return DB::table( with(new Clinic)->getTable() );	}			public static function filter_folder()	{		return __NAMESPACE__ . '\\Filters\\';	}	}