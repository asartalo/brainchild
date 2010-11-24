<?php

namespace Brainchild;

use
  \Brainchild\Entities\User,
  \Brainchild\Entities\NullUser;

class Manager {
  
  private 
    $my_account,
    $user_repo,
    $project_manager;
  
  function __construct(
    Repositories\UserRepository $user_repo,
    ProjectManager $project_manager
  ) {
    $this->my_account = new NullUser; 
    $this->user_repo = $user_repo;
    $this->project_manager = $project_manager;
  }
  
  function registerUser(array $user_options) {
    return $this->user_repo->save(new User($user_options));
  }
  
  function getUser($username) {
    return $this->user_repo->findByName($username);
  }
  
  function login($username, $password) {
    $user = $this->getUser($username);
    $user->takeControl($password);
    if ($user->isControllable()) {
      $feedback = new Feedback(
        'You are now logged in. :)',
        Feedback::USER_LOGIN_SUCCESS
      );
      $this->my_account = $user;
    } else {
      $feedback = new Feedback(
        'Unable to login. The user and/or the password you specified is wrong.',
        Feedback::USER_LOGIN_FAILURE
      );
    }
    return $feedback;
  }
  
  function logout() {
    $this->my_account->revokeControl();
    $this->my_account = new NullUser;
  }
  
  function getMyAccount() {
    return $this->my_account;
  }
  
  function getMyProjectManager() {
    if ($this->my_account->isControllable()) {
      return $this->project_manager;
    }
    return new \Brainchild\NullProjectManager;
  }
  
}
