<?php

namespace RM2\RecipesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IngredientRepository extends EntityRepository {
    
    public function getIngredientsWithNameLike($name) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('i')
           ->from('RM2RecipesBundle:Ingredient', 'i')
           ->where($qb->expr()->like('i.name', ':name'))
           ->setParameter('name', '%' . $name . '%');
        
        return $qb->getQuery()->getResult();
    }
}

?>
