<?php
//use Cart\SessionStore;
use Cart\CartItem;

class TurboCart{
  public $cart;
  public function __construct()
  {
    require __DIR__ . '/session.php';
    $cart = new Cart\Cart('cart1', new SessionStore());
    $this->cart = $cart;
    if($this->cart->getStore()->get('cart1') != false)
    {
      $this->cart->restore();
    }

  }

  public function insert($data)
  {
    $item = new CartItem;
    foreach($data as $key => $value)
    {
      $item->$key = $value;
    }
    $this->cart->add($item);
    $this->cart->save();
  }

  public function findItemId($key)
  {
    return $this->cart->all()[$key]->getId();
  }

  public function __call($method, $params)
  {
    $result = call_user_func_array(array($this->cart, $method), $params);
    $this->cart->save();
    return $result;
  }
}

App::register('cart', new TurboCart());
