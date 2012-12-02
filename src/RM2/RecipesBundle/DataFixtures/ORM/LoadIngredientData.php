<?php

namespace RM2\RecipesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RM2\RecipesBundle\Entity\Ingredient;


class LoadIngredientData extends LoadRM2Data implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $ingredients = $this->getModelFixtures();
        
        foreach ($ingredients['Ingredient'] as $refName => $columns ) {
            $ingredient = new Ingredient();
            $ingredient->setName($columns['name']);
            
            $manager->persist($ingredient);
            $manager->flush();
            
            $this->addReference('Ingredient_' . $refName, $ingredient);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
    
    public function getModelFile() {
        return 'ingredients';
    }
    
}

?>
