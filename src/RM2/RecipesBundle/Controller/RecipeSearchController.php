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
    
    public function indexAction() {
        
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
