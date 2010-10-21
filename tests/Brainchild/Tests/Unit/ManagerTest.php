<?php

namespace Brainchild\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Brainchild\Manager, \Brainchild\Entities\User;

class ManagerTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->user = new User(array(
      'username' => 'john',
      'password' => 'secret',
      'email'    => 'john@example.com'
    ));
    $this->em = $this->getMock('EntityManager', array('find'));
    $this->manager = new Manager($this->em);
    $this->manager->registerUser($this->user);
  }
  
  function testGettingUser() {
    $this->assertEquals('john', $this->manager->getUser('john')->getUsername());
  }
  
  function testRegisteringAnotherUserWithSameUsername() {
    $user = new User(array(
      'username' => 'john',
      'password' => 'foo',
      'email'    => 'email@address.com'
    ));
    $this->assertFalse($this->manager->registerUser($user));
  }
  
  function testLoggingIn() {
    $this->manager->login('john', 'secret');
    $me = $this->manager->getMyAccount();
    $this->assertEquals('john@example.com', $me->getEmail());
  }
  
  function testLogInFailure() {
    $feedback = $this->manager->login('john', 'wrong_password');
    $this->assertEquals(
      'The password for john is wrong.',
      $feedback->getMessage()
    );
    $this->assertEquals(
      Manager::MESSAGES_WRONG_PASSWORD,
      $feedback->getCode()
    );
  }
  
  function testLoginFailure2() {
    $feedback = $this->manager->login('foo', 'secret');
    $this->assertEquals(
      'Did not find user.',
      $feedback->getMessage()
    );
    $this->assertEquals(
      Manager::MESSAGES_LOGIN_UNKNOWN_USER,
      $feedback->getCode()
    );
  }
  
}
