<?php

namespace Brainchild;

class Manager {

  const 
    MESSAGES_WRONG_PASSWORD = 100,
    MESSAGES_LOGIN_UNKNOWN_USER = 101;
  
  private $my_account, $users = array();
  
  function registerUser(Entities\User $user) {
    if (!array_key_exists($user->getUsername(), $this->users)) {
      $this->users[$user->getUsername()] = $user;
      return true;
    }
    return false;
  }
  
  function getUser($username) {
    if (array_key_exists($username, $this->users)) {
      return $this->users[$username];
    }
    return false;
  }
  
  function login($username, $password) {
    $user = $this->getUser($username);
    if (!$user) {
      return $feedback = new Feedback(
        "Did not find user.",
        self::MESSAGES_LOGIN_UNKNOWN_USER
      );
    }
    if ($user->isPasswordMatch($password)) {
      $user->takeControl($password);
    } else {
      return $feedback = new Feedback(
        "The password for {$username} is wrong.",
        self::MESSAGES_WRONG_PASSWORD
      );
    }
    if ($user->isControllable()) {
      $this->my_account = $user;
    }
  }
  
  function getMyAccount() {
    return $this->my_account;
  }
  
}
