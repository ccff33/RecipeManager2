<?php
namespace RM2\RecipesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RM2\RecipesBundle\Entity\Recipe;
use RM2\RecipesBundle\Form\RecipeType;

/**
 * @Route("/recipe/search")
 */
class RecipeSearchController extends Controller {
    
    protected $defaultPageSize = 10;
    
    /**
     * @Route("/by-ingredients", name="recipe_search_by_ingredients")
     * @Template()
     */
    public function byIngredientsAction(Request $request) {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        
        $ids = $request->get('ingredients');
        
        $qb->select('r')
           ->from('RM2RecipesBundle:Recipe', 'r')
           ->leftJoin('r.ingredients', 'i')
           ->where($qb->expr()->in('i.id', ':ids'))
           ->groupBy('r.id')
           ->having($qb->expr()->eq($qb->expr()->count('r'), count($ids)))
           ->setParameter('ids', $ids);
        
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $qb->getQuery()->getResult(),
            $request->get('page', 1),
            $this->defaultPageSize
        );
        
        return array(
            'pagination' => $pagination,
        );
    }
    
    /**
     * @Route("/name-like", name="recipe_search_name_like")
     * @Template()
     */
    public function nameLikeAction(Request $request) {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
     
        $qb->select('r')
           ->from('RM2RecipesBundle:Recipe', 'r')
           ->where($qb->expr()->like('r.name', ':name'))
           ->setParameter('name', '%' . $request->get('name') . '%');
        
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $qb,
            $request->get('page', 1),
            $this->defaultPageSize
        );
        
        return array(
            'pagination' => $pagination
        );
    }
}

?>
