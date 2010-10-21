<?php

namespace Brainchild\Tests\Unit\Entities;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Brainchild\Entities\User;

class UserTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->user = new User(array(
      'username' => 'juan',
      'password' => 'secret',
      'email'    => 'john@example.com'
    ));
  }

  function testBasicCreatingAUser() {
    $this->assertEquals('juan', $this->user->getUsername());
  }
  
  function testValidateUser() {
    $this->assertTrue($this->user->isPasswordMatch('secret'));
  }
  
  function testValidateUserUnsuccessful() {
    $this->assertFalse($this->user->isPasswordMatch('foo'));
  }
  
  function testGettingEmail() {
    $this->assertEquals('john@example.com', $this->user->getEmail());
  }
  
  function testIsControllableDefaultsToFalse() {
    // TODO: This may make client code dependent too much on state. Try null
    // object pattern instead.
    $this->assertFalse($this->user->isControllable());
  }
  
  function testIsControllableSwitchesToTrueWhenLoggedIn() {
    $this->user->takeControl('secret');
    $this->assertTrue($this->user->isControllable());
  }
  
}
