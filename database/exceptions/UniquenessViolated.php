<?php

class UniquenessViolated extends Exception
{
  public function __construct($conflict, $table)
  {
    // parent::__construct("$conflict in $table");
    parent::__construct("$conflict");
  }
}