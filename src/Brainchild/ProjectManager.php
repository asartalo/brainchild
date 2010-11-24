<?php

namespace Brainchild;

class ProjectManager {
  
  private $repository;
  
  function isNull() {
    return false;
  }
  
  function __construct(ProjectRepository $repository) {
    $this->repository = $repository;
  }
  
}
