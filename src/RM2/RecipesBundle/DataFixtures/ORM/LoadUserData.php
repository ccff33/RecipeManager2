<?php

namespace RM2\RecipesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RM2\UserBundle\Entity\User;


class LoadUserData extends LoadRM2Data implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $users = $this->getModelFixtures();
        
        foreach ($users['User'] as $refName => $columns) {
            $user = new User();
            $user->setEnabled($columns['enabled']);
            $user->setUsername($columns['username']);
            $user->setEmail($columns['email']);
            $user->setPlainPassword($columns['password']);
        
            $manager->persist($user);
            $manager->flush();
        
            $this->addReference('User_' . $refName, $user);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
    
    public function getModelFile() {
        return 'users';
    }
    
}

?>
