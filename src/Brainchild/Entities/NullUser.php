<?php

namespace Brainchild\Entities;

class NullUser extends User {

  function __construct() {
    
  }

  function isNull() {
    return true;
  }

}
