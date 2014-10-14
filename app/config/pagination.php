<?php

/**
 * Default pagination styling for automatic paginator
 *
 * You can also specify more if you need.
 */

return array(
    'default' => 'bootstrap',
    'bootstrap' => array(
        'wrapper'       => '<div>',
        'container'     => '<ul class="pagination">',
        'normal'        => '<li><a href="[page_link]">[page_no]</a>',
        'active'        => '<li class="active"><a href="[page_link]">[page_no]</a>',
        'container_end' => '</ul>',
        'wrapper_end'   => '</div>',
    ),
    'foundation' => array(
        'wrapper'       => '<div>',
        'container'     => '<ul class="pagination">',
        'normal'        => '<li><a href="[page_link]">[page_no]</a>',
        'active'        => '<li class="current"><a href="[page_link]">[page_no]</a>',
        'container_end' => '</ul>',
        'wrapper_end'   => '</div>',
    )
);