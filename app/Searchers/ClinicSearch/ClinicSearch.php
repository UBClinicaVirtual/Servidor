<?phpnamespace App\Searchers\ClinicSearch;use App\Clinic;use Illuminate\Http\Request;use Illuminate\Database\Eloquent\Builder;/** Source: https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db*/class ClinicSearch{    /*    |--------------------------------------------------------------------------    | Clinic Search    |--------------------------------------------------------------------------    |    | This class builds the query to fetch the clinics based on the filters sent on	| the json request. It dynamics load the filter class from the \Filters\ folder    |    */    public static function apply(Request $filters)    {        $query = static::applyDecoratorsFromRequest( $filters, (new Clinic)->newQuery() );		        return static::getResults($query);    }    	/*	| Fetchs the class that implements Filters in the Filters folder	*/    private static function applyDecoratorsFromRequest(Request $request, Builder $query)    {        foreach ($request->all() as $filterName => $value) {            $decorator = static::createFilterDecorator($filterName);            if (static::isValidDecorator($decorator)) {                $query = $decorator::apply($query, $value);            }        }        return $query;    }        private static function createFilterDecorator($name)    {        return __NAMESPACE__ . '\\Filters\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));    }        private static function isValidDecorator($decorator)    {        return class_exists($decorator);    }    private static function getResults(Builder $query)    {        return $query->get();    }}