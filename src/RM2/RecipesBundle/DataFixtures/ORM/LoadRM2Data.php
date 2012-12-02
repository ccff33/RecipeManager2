<?php

namespace RM2\RecipesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

abstract class LoadRM2Data extends AbstractFixture implements ContainerAwareInterface {
    
    protected $container;
    
    abstract function getModelFile();
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    public function getModelFixtures() {
        $path = realpath(dirname(__FILE__) . '/../Fixtures');
        $fixtures = Yaml::parse(file_get_contents($path . '/' . $this->getModelFile(). '.yml'));
        return $fixtures;
    }
    
}

?>
