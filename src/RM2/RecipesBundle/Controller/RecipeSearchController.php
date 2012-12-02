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
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('RM2RecipesBundle:Recipe');
        
        $recipes = $repository->getRecipesWithAllIngredients($request->get('ingredients'));
        
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $recipes,
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
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('RM2RecipesBundle:Recipe');
        
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $repository->getRecipesWithNameLike($request->get('name')),
            $request->get('page', 1),
            $this->defaultPageSize
        );
        
        return array(
            'pagination' => $pagination
        );
    }
}

?>
