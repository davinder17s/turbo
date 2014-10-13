<?php

use Moltin\Cart\Cart as Shop;
use Moltin\Cart\Storage\Session;
use Moltin\Cart\Storage\TurboSession;
use Moltin\Cart\Identifier\Cookie;

require __DIR__ . '/session.php';

$cart = new Shop(new TurboSession, new Cookie);

App::register('cart', $cart);
