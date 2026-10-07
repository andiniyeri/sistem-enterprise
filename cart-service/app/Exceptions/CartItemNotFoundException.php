<?php

namespace App\Exceptions;

use Exception;

class CartItemNotFoundException extends Exception
{
   public function __construct(int $id)
   {
       parent::__construct("Item Keranjangdengan id {$id} tidak ditemukan");
   } 
}
