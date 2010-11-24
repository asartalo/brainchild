<?php

namespace Brainchild\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity(repositoryClass="Brainchild\Repositories\ProjectRepository")
 */
class Project {
  
  /** @Column */
  private $name;
  
  private $description;
  
  private $user;
  
  private $difficulty = 'medium';
  
  private $activity   = 'average';
  
  private $url = '';
  
  private $progress = 'idea';
  
  private $collaborators;
  
  private $logs;
  
  function __construct(User $user, $name, array $options = array()) {
    if (!$user->isControllable()) {
      throw new \Brainchild\Entities\Project\Exception\CreateFailUnauthorized(
        'You are not authorized to create a project for this person.'
      );
    }
    $this->user = $user;
    $this->name = $name;
    if (isset($options['description'])) {
      $this->description = $options['description'];
    }
    $this->collaborators = new ArrayCollection;
    $this->logs = new ArrayCollection;
  }
  
  function getName() {
    return $this->name;
  }
  
  function setName($name) {
    if ($this->user->isControllable()) {
      $this->name = $name;
    }
  }
  
  function getDescription() {
    return $this->description;
  }
  
  function setDescription($description) {
    if ($this->user->isControllable()) {
      $this->description = $description;
    }
  }
  
  function getOwner() {
    return $this->user;
  }
  
  function getDifficulty() {
    return $this->difficulty;
  }
  
  function getActivity() {
    return $this->activity;
  }
  
  function getUrl() {
    return $this->url;
  }
  
  function getProgress() {
    return $this->progress;
  }
  
  function getCollaborators() {
    return $this->collaborators;
  }
  
  function getLogs() {
    return $this->logs;
  }
  
}
