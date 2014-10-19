<?php
/**
Usage example:

$countries = DB::table('countries');
$filters = array(
    'code.multiple' => $this->get('country'),
    'code.one' => $this->get('exact'),
    'country.like' => $this->get('code'),
    'id.range' => array($this->get('min'), $this->get('max'))
);
$results = Filter::make($countries, $filters);

print_r($results->get());
 *
 */

class Filter {
    protected $results;
    protected $filters;
    protected $finalResults;

    public function __construct($results, $filters)
    {
        $this->results = $results;
        $this->finalResults = $results; // initialize final results as same
        $this->filters = $filters;
    }

    public static function make($results, $filters)
    {
        $filter = new Filter($results, $filters);
        $filter->run();
        return $filter->getResults();
    }

    public function run()
    {
        $filters = $this->filters;
        foreach ($filters as $filter_info => $against) {
            $filter = explode('.', $filter_info); // country.multiple
            $column = $filter[0]; // country
            $filter_type = $filter[1]; // multiple
            $this->$filter_type($column, $against);
        }
    }

    public function getResults()
    {
        return $this->finalResults;
    }

    /* Filters start */
    protected function multiple($column, $against)
    {
        if (!empty($against)) {
            $filtered = $this->finalResults->whereIn($column, $against);
            $this->finalResults = $filtered;

        }
    }

    protected function one($column, $against)
    {
        if (!empty($against)) {
            $filtered = $this->finalResults->where($column, '=', $against);
            $this->finalResults = $filtered;
        }
    }

    protected function like($column, $against)
    {
        if (!empty($against)) {
            if (is_array($against)) {
                $against = $against[0];
            }
            $filtered = $this->finalResults->where($column, 'LIKE', '%'.$against.'%');
            $this->finalResults = $filtered;
        }
    }

    protected function range($column, $against)
    {
        if (!empty($against)) {
            if (isset($against[0]) && isset($against[1])) {
                if (!empty($against[0]) || !empty($against[1])) {
                    $filtered = $this->finalResults->whereBetween($column, $against);
                    $this->finalResults = $filtered;
                }
            }
        }
    }
    /* Filters end */

}