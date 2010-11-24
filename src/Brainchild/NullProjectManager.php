<?php

namespace Brainchild;

class NullProjectManager extends \Brainchild\ProjectManager {
  
  function isNull() {
    return true;
  }
  
  function __construct() {
    
  }
  
}
