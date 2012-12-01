<?php

namespace RM2\RecipesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RM2\RecipesBundle\Entity\Ingredient;
use RM2\RecipesBundle\Form\IngredientType;

/**
 * Ingredient controller.
 *
 * @Route("/ingredient")
 */
class IngredientController extends Controller
{
    
    /**
     * Finds and displays a Ingredient entity.
     *
     * @Route("/{id}/show", name="ingredient_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RM2RecipesBundle:Ingredient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ingredient entity.');
        }
        return array(
            'entity' => $entity
        );
    }

    /**
     * Displays a form to create a new Ingredient entity.
     *
     * @Route("/new", name="ingredient_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ingredient();
        $form   = $this->createForm(new IngredientType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Ingredient entity.
     *
     * @Route("/create", name="ingredient_create")
     * @Method("POST")
     * @Template("RM2RecipesBundle:Ingredient:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Ingredient();
        $form = $this->createForm(new IngredientType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ingredient_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
}
