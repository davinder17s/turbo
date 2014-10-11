<?php

class Paginator{

    public $links;
    public $results;
    public $current_page;
    public $total;

    function getCurrentPage($count)
    {
        $app = App::instance();
        $page = max(1, $app->get()->get('page'));
        $this->total = $count;
        $this->current_page = $page;
        return $page;
    }

    function make($results, $count, $per_page)
    {
        $defaults = array(
            'wrapper' => '<div class="pagination">',
            'wrapper_end' => '</div>',
            'container' => '<ul>',
            'container_end' => '</ul>',
            'normal' => '<li><a href="[page_link]">[page_no]</a>',
            'active' => '<li class="active"><a href="[page_link]">[page_no]</a>'
        );
        $config = array();
        if (file_exists(APPDIR . 'config/pagination.php')) {
            $config = require APPDIR . 'config/pagination.php';
        }
        $layout = array_merge($defaults, $config);

        $pages = ceil($count / $per_page);

        $links_html = $layout['wrapper'] . "\r\n";
        $links_html .= $layout['container'] . "\r\n";

        $request = App::instance()->request();

        $base_url = $request->getBaseUrl();
        $path_info = $request->getPathInfo();
        $query = $request->query;

        $query->set('page', '__pageno__');


        $link = $base_url . $path_info . '?' . http_build_query($query->all());
        $link = str_replace('__pageno__', '[page_no]', $link);

        for($i = 1; $i<= $pages; $i++)
        {
            if ($i != $this->current_page) {
                $link_normal = str_replace('[page_link]', $link, $layout['normal']);
                $link_normal = str_replace('[page_no]', $i, $link_normal);
                $links_html .= $link_normal . "\r\n";
            } else {
                $link_active = str_replace('[page_link]', $link, $layout['active']);
                $link_active = str_replace('[page_no]', $i, $link_active);
                $links_html .= $link_active . "\r\n";
            }
        }
        $links_html .= $layout['container_end'] . "\r\n";
        $links_html .= $layout['wrapper_end'] . "\r\n";

        $this->results = $results;
        $this->links = $links_html;
        return $this;
    }

    function get()
    {
        return $this->results;
    }

    function links()
    {
        return $this->links;
    }
};