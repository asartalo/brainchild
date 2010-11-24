<?php

namespace Brainchild\Entities;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity(repositoryClass="Brainchild\Repositories\UserRepository")
 */
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
  
  function revokeControl() {
    $this->state_controllable = false;
  }
  
  function setEmail($email) {
    if ($this->isControllable()) {
      $this->email = $email;
    }
  }
  
  function changePassword($old_password, $new_password) {
    $this->password = $new_password;
  }
  
}
