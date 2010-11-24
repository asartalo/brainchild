<?php

namespace Brainchild\Tests\Unit\Entities;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Brainchild\Entities\Project, \Brainchild\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectTest extends \PHPUnit_Framework_TestCase {
  
  private $user_options = array(
    'username' => 'juan',
    'password' => 'secret',
    'email'    => 'john@example.com'
  );
  
  function setUp() {
    $this->user = new User($this->user_options);
    $this->project = $this->createProject();
  }
  
  private function createProject(array $options = array()) {
    $this->user->takeControl('secret');
    return new Project($this->user, 'Foo Project', $options);
  }
  
  function testBasicInfo() {
    $this->assertEquals('Foo Project', $this->project->getName());
  }
  
  /**
   * @dataProvider dataDefaultValues
   */
  function testDefaultValues($value, $reader) {
    $this->assertEquals(
      $value, $this->project->$reader()
    );
  }
  
  function dataDefaultValues() {
    return array(
      array('', 'getDescription'),
      array('', 'getUrl'),
      array('medium', 'getDifficulty'),
      array('average', 'getActivity'),
      array('idea', 'getProgress'),
      array(new ArrayCollection, 'getCollaborators'),
      array(new ArrayCollection, 'getLogs')
    );
  }
  
  function testGettingOwner() {
    $this->assertEquals($this->user, $this->project->getOwner());
  }
  
  /**
   * @dataProvider dataChangingProperty
   */
  function testChangingPropertySuccess($writer, $reader, $value) {
    $this->project->$writer($value);
    $this->assertEquals($value, $this->project->$reader());
  }
  
  function dataChangingProperty() {
    return array(
      array('setName', 'getName', 'Bar Project'),
      array('setDescription', 'getDescription', 'Boo Far')
    );
  }
  
  /**
   * @dataProvider dataChangingProperty
   */
  function testChangingPropertyWhenNotInControl($writer, $reader, $value) {
    $this->user->revokeControl();
    $this->project->$writer($value);
    $this->assertNotEquals($value, $this->project->$reader());
  }
  
  function testSettingDescriptionOnCreation() {
    $project = $this->createProject(array('description' => 'Foo Bar'));
    $this->assertEquals('Foo Bar', $project->getDescription());
  }
  
  function testProjectCreationFailsWhenUserIsNotControllable() {
    $this->setExpectedException(
      '\Brainchild\Entities\Project\Exception\CreateFailUnauthorized'
    );
    $user = new User($this->user_options);
    new Project($user, 'Foo Project');
  }
  
}
