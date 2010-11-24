<?php

namespace Brainchild;

class Feedback { // implements Feedback_Interface {
  
  private $message, $code;
  
  const
    USER_REGISTER_SUCCESS = 100,
    USER_REGISTER_FAILURE = 101,
    USER_LOGIN_SUCCESS    = 150,
    USER_LOGIN_FAILURE    = 151
    ;
  
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
