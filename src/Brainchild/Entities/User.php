<?php

namespace Brainchild\Entities;

/** @Entity */
class User {
  
  /** @Column */
  private $username;
  
  private $password;
  
  private $email;
  
  private $state_controllable = false;
  
  function __construct($options) {
    $this->username = $options['username'];
    $this->password = $options['password'];
    $this->email    = $options['email'];
  }
  
  function isNull() {
    return false;
  }
  
  function getUsername() {
    return $this->username;
  }
  
  function isPasswordMatch($password) {
    return $this->password === $password;
  }
  
  function getEmail() {
    return $this->email;
  }
  
  function isControllable() {
    return $this->state_controllable;
  }
  
  function takeControl($password) {
    if ($this->isPasswordMatch($password)) {
      $this->state_controllable = true;
    }
  }
  
}
