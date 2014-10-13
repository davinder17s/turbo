<?php
namespace Moltin\Cart\Storage;

use Moltin\Cart\Storage\Session;
use Moltin\Cart\Item;

class TurboSession extends Session {
  /**
   * Add or update an item in the cart
   *
   * @param  Item   $item The item to insert or update
   * @return void
   */
  public function insertUpdate(Item $item)
  {
      static::$cart[$this->id][$item->identifier] = $item;
      $_SESSION['cart'] = serialize(static::$cart);
  }
}
