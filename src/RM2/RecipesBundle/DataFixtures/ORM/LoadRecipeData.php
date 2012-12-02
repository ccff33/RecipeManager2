<?php

namespace RM2\RecipesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RM2\RecipesBundle\Entity\Recipe;


class LoadRecipeData extends LoadRM2Data implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $recipes = $this->getModelFixtures();
        
        foreach ($recipes['Recipe'] as $refName => $columns) {
            $recipe = new Recipe();
            $recipe->setName($columns['name']);
            $recipe->setCreator($this->getReference('User_' . $columns['User']));
            $recipe->setDescription($columns['description']);
            foreach ($columns['Ingredient'] as $ingredient) {
                $recipe->addIngredient($this->getReference('Ingredient_' . $ingredient));
            }
        
            $manager->persist($recipe);
            $manager->flush();
        
            $this->setReference('Recipe_' . $refName, $recipe);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }
    
    public function getModelFile() {
        return 'recipes';
    }
    
}

?>
