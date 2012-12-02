<?php

namespace RM2\RecipesBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RecipeRepository extends EntityRepository {
    
    public function getRecipesWithAllIngredients($ids) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
           ->from('RM2RecipesBundle:Recipe', 'r')
           ->leftJoin('r.ingredients', 'i')
           ->where($qb->expr()->in('i.id', ':ids'))
           ->groupBy('r.id')
           ->having($qb->expr()->eq($qb->expr()->count('r'), count($ids)))
           ->setParameter('ids', $ids);
        
        return $qb->getQuery()->getResult();
    }
    
    public function getRecipesWithNameLike($name) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
           ->from('RM2RecipesBundle:Recipe', 'r')
           ->where($qb->expr()->like('r.name', ':name'))
           ->setParameter('name', '%' . $name . '%');
        
        return $qb;
    }
}

?>
