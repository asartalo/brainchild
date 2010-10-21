<?php

namespace Brainchild;

class Feedback { // implements Feedback_Interface {
  
  private $message, $code;
  
  function __construct($message, $code) {
    $this->message = $message;
    $this->code = $code;
  }
  
  function getMessage() {
    return $this->message;
  }
  
  function getCode() {
    return $this->code;
  }
}
