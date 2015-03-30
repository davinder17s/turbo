<?php
/**
 * Datatables handler and helper with ajax support
 */
class Datatables
{
    protected $app;
    protected $columns;
    protected $order;
    protected $start = 0;
    protected $length = 0;
    protected $search;
    protected $draw;
    protected $query;
    protected $processed_query;
    protected $count;
    protected $filtered;
    protected $additional_columns = array();
    protected $db_columns = array();

    public function __construct()
    {
        // constructor
        $this->app = App::instance();

        // Get variables by post request
        if ($this->app->post('columns')) {
            $columns = $this->app->post()->get('columns');
            $order = $this->app->post()->get('order');
            $start = $this->app->post()->get('start');
            $length = $this->app->post()->get('length');
            $search = $this->app->post()->get('search');
            $draw = $this->app->post()->get('draw');
        } else { // Get variables by get request
            $columns = $this->app->get()->get('columns');
            $order = $this->app->get()->get('order');
            $start = $this->app->get()->get('start');
            $length = $this->app->get()->get('length');
            $search = $this->app->get()->get('search');
            $draw = $this->app->get()->get('draw');
        }
        // Save for later use
        $this->columns = $columns;
        $this->order = $order;
        $this->start = $start;
        $this->length = $length;
        $this->search = $search;
        $this->draw = $draw;
    }

    public function get($query)
    {
        if (is_string($query)) {
            $query = $this->query($query);
        }
        $this->db_columns = array_keys($query->first());
        $this->query = $query;
        $this->processed_query = $query;

        $data = $this->render();
        $output = array(
            "iTotalRecords"        => $this->count,
            "iTotalDisplayRecords" => $this->filtered,
            "aaData"               => $data,
        );
        return \Symfony\Component\HttpFoundation\JsonResponse::create($output);
    }

    public function query($sql)
    {
        return DB::table(DB::raw("($sql) as datatable_raw"));
    }

    protected function render()
    {
        // total record count
        $this->count = (int)$this->query->count();

        // Searching
        $this->process_search();
        // Ordering
        $this->process_ordering();

        // set filtered record before limiting
        $this->filtered = (int)$this->processed_query->count();

        // limiting
        $this->process_limiting();
        $filtered_query = $this->processed_query->get();

        $final_records = array();
        foreach ($filtered_query as $row) {
            // process add columns
            foreach ($this->additional_columns as $column) {
                $column_name = $column['name'];
                if (is_callable($column['callback'])) {
                    // user modified data
                    $row[$column_name] = call_user_func_array($column['callback'], array((object)$row));
                } else {
                    $row[$column_name] = '';
                }
            }
            $final_records[] = $row;
        }
        return $final_records;
    }

    protected function process_search()
    {
        $query = $this->processed_query;
        foreach ($this->columns as $column) {
            if ($column['searchable'] == true && in_array($column['data'], $this->db_columns)) {
                if ($column['search']['value']) {
                    $query->orWhere($column['data'], 'LIKE', '%' . $column['search']['value'] . '%');
                } else {
                    $query->orWhere($column['data'], 'LIKE', '%' . $this->search['value'] . '%');
                }
            }
        }
        $this->processed_query = $query;
    }

    protected function process_ordering()
    {
        $query = $this->processed_query;
        foreach ($this->columns as $key => $column) {
            if ($column['orderable'] == true && in_array($column['data'], $this->db_columns)) {
                foreach ($this->order as $order) {
                    if ($order['column'] == $key) {
                        $query->orderBy($column['data'], $order['dir']);
                    }
                }
            }
        }
        $this->processed_query = $query;
    }

    protected function process_limiting()
    {
        $query = $this->processed_query;
        $query->skip($this->start)->take($this->length);
        $this->processed_query = $query;
    }

    public function addColumn($name, $callback)
    {
        $this->additional_columns[] = array(
            'name' => $name,
            'callback' => $callback
        );
    }

    public function editColumn($name, $callback)
    {
        $this->additional_columns[] = array(
            'name' => $name,
            'callback' => $callback
        );
    }
}

App::register('datatables', new Datatables());