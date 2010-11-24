<?php

namespace Brainchild\Tests\Functional;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Brainchild\Entities\User, \Brainchild\Manager;

class UserManagementTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    $this->markTestIncomplete();
    $this->user = new User(array(
      'username' => 'john',
      'password' => 'secret',
      'email'    => 'john@example.com'
    ));
    $this->manager = new Manager();
    $this->manager->registerUser($this->user);
  }
  
  function testCreatingAUser() {
    $this->assertEquals(
      'john@example.com', $this->manager->getUser('john')->getEmail()
    );
  }
  
  function testLoginUser() {
    $this->manager->login('john', 'secret');
    $me = $this->manager->getMyAccount();
    $this->assertEquals('john', $me->getUsername());
    $this->assertEquals(true, $me->isControllable());
  }
  
  
}
