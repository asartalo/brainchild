<?php

// Doctrine Bootstrap
$lib = realpath(__DIR__ . '/../vendor/doctrine/lib');
require $lib . '/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader(
  'Doctrine\Common', $lib . '/vendor/doctrine-common/lib'
);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader(
  'Doctrine\DBAL', $lib . '/vendor/doctrine-dbal/lib'
);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', $lib);
$classLoader->register();

// Brainchild Bootstrap
$classLoader = new \Doctrine\Common\ClassLoader(
  'Brainchild', realpath(__DIR__ . '/../src/')
);
$classLoader->register();

