<?php

namespace Brainchild\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use 
  \Brainchild\Manager, 
  \Brainchild\Entities\User,
  \Brainchild\Entities\NullUser,
  \Brainchild\Feedback;

class ManagerTest extends \PHPUnit_Framework_TestCase {

  private $null_user;

  function setUp() {
    $this->user = $this->getMock(
      'Brainchild\Entities\User', array(), array(), '', FALSE
    );
    $this->user_repo = $this->getMock(
      'Brainchild\Repositories\UserRepository', array('findByName', 'save')
    );
    $this->project_manager = $this->getMock(
      'Brainchild\ProjectManager', array(), array(), '', FALSE
    );
    $this->repoReturnsUser();
    $this->manager = new Manager($this->user_repo, $this->project_manager);
  }
  
  private function repoReturnsUser() {
    $this->user_repo->expects($this->any())
      ->method('findByName')
      ->will($this->returnValue($this->user));
  }
  
  function testGettingUserCallsFindOnEm() {
    $this->user_repo->expects($this->once())
      ->method('findByName')
      ->with('john');
    $this->manager->getUser('john');
  }
  
  function testGettingUserReturnsValueFromUserRepo() {
    $this->user_repo->expects($this->once())
      ->method('findByName')
      ->will($this->returnValue($this->user));
    $this->assertEquals(
      $this->user, $this->manager->getUser('john')
    );
  }
  
  function testRegisteringAUserAddsItToUserRepository() {
    $user_options = array(
      'username' => 'john',
      'password' => 'secret',
      'email'    => 'john@example.com'
    );
    $this->user_repo->expects($this->once())
      ->method('save')
      ->with(new User($user_options));
    $this->manager->registerUser($user_options);
  }
  
  function testRegisteringAUserReturnsAFeedback() {
    $user_options = array(
      'username' => 'john',
      'password' => 'secret',
      'email'    => 'john@example.com'
    );
    $feedback = new Feedback('foo', 2);
    $this->user_repo->expects($this->once())
      ->method('save')
      ->will($this->returnValue($feedback));
    $this->assertEquals(
      $feedback,
      $this->manager->registerUser($user_options)
    );
  }
  
  function testLoggingInSearchesUserFromRepository() {
    $this->user_repo->expects($this->once())
      ->method('findByName')
      ->with('john');
    $this->manager->login('john', 'secret');
  }
  
  function testLoggingInAttempstToMakeUserControllable() {
    $this->user->expects($this->once())
      ->method('takeControl')
      ->with('secret');
    $this->manager->login('juan', 'secret');
  }
  
  function testLoggingInTestsReturnedUserForControl() {
    $this->user->expects($this->once())
      ->method('isControllable');
    $this->manager->login('juan', 'secret');
  }
  
  private function userNotControllable() {
    $this->user->expects($this->once())
      ->method('isControllable')
      ->will($this->returnValue(false));
  }
  
  private function userIsControllable() {
    $this->user->expects($this->any())
      ->method('isControllable')
      ->will($this->returnValue(true));
  }
  
  function testLoggingInReturnsFeedbackFailureMessageWhenControlTestFails() {
    $this->userNotControllable();
    $feedback = $this->manager->login('juan', 'secret');
    $this->assertEquals(
      Feedback::USER_LOGIN_FAILURE, $feedback->getCode()
    );
    $this->assertEquals(
      'Unable to login. The user and/or the password you specified is wrong.',
      $feedback->getMessage()
    );
  }
  
  function testLoggingInReturnsFeedbackSuccessMessageWhenControlTestSucceeds() {
    $this->userIsControllable();
    $feedback = $this->manager->login('juan', 'secret');
    $this->assertEquals(
      Feedback::USER_LOGIN_SUCCESS, $feedback->getCode()
    );
    $this->assertEquals(
      'You are now logged in. :)', $feedback->getMessage()
    );
  }
  
  function testLoginMakesMyAccountAvailable() {
    $this->userIsControllable();
    $this->manager->login('foo', 'bar');
    $this->assertEquals($this->user, $this->manager->getMyAccount());
  }
  
  private function getNullUser() {
    if (!$this->null_user) {
      $this->null_user = new NullUser;
    }
    return $this->null_user;
  }
  
  function testLoginFailureReturnsNullUserForMyAccount() {
    $this->userNotControllable();
    $this->manager->login('foo', 'bar');
    $this->assertEquals($this->getNullUser(), $this->manager->getMyAccount());
  }
  
  function testGetMyAccountReturnsNullUserByDefault() {
    $this->assertEquals($this->getNullUser(), $this->manager->getMyAccount());
  }
  
  function testLoggingOutReturnsNullUserForMyAccount() {
    $this->userIsControllable();
    $this->manager->login('foo', 'bar');
    $this->manager->logout();
    $this->assertEquals($this->getNullUser(), $this->manager->getMyAccount());
  }
  
  function testLoggingOutRevokesUserControl() {
    $this->userIsControllable();
    $this->manager->login('foo', 'bar');
    $user = $this->manager->getMyAccount();
    $user->expects($this->once())
      ->method('revokeControl');
    $this->manager->logout();
  }
  
  function testManagerReturnsProjectManagerForUserWhenLoggedIn() {
    $this->userIsControllable();
    $this->manager->login('foo', 'bar');
    $this->assertSame(
      $this->project_manager, $this->manager->getMyProjectManager()
    );
  }
  
  function testReturnNullProjectManagerWhenUserIsNotLoggedIn() {
    $this->assertEquals(
      new \Brainchild\NullProjectManager, $this->manager->getMyProjectManager()
    );
  }
  
}
