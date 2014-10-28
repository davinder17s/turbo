<?php

class UserPaginator
{
  protected $default_layout = false;

  public function getDefault()
  {
    return $this->default_layout;
  }

  public function setDefault($layout_name)
  {
    $this->default_layout = $layout_name;
  }
}
