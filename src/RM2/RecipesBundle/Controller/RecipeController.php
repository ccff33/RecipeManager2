<?php

namespace RM2\RecipesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RM2\RecipesBundle\Entity\Recipe;
use RM2\RecipesBundle\Form\RecipeType;

class RecipeController extends Controller {

    /**
     * @Route("/", name="recipe")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getEntityManager();
        $recipeCount = $em->createQuery('SELECT COUNT(r) FROM RM2RecipesBundle:Recipe r')
                          ->getSingleScalarResult();
        return array(
            'recipeCount' => $recipeCount
        );
    }

    /**
     * @Route("/{id}/show", name="recipe_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RM2RecipesBundle:Recipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        return array(
            'entity' => $entity
        );
    }

    /**
     * @Route("/new", name="recipe_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Recipe();
        $form = $this->createForm(new RecipeType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="recipe_create")
     * @Method("POST")
     * @Template("RM2RecipesBundle:Recipe:new.html.twig")
     */
    public function createAction(Request $request) {
        $creator = $this->getAuthUser();
        $entity = new Recipe();
        $form = $this->createForm(new RecipeType(), $entity);
        $form->bind($request);
        $entity->setCreator($creator);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('recipe_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit")
     * @Template()
     */
    public function editAction($id) {
        $authUser = $this->getAuthUser();
        $entity = $this->getRecipeById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }

        if ($entity->getCreator() != $authUser) {
            $this->get('session')->getFlashBag()->add('notice', 'Not allowed');
            return $this->redirect($this->generateUrl('recipe_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new RecipeType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * @Route("/{id}/update", name="recipe_update")
     * @Method("POST")
     * @Template("RM2RecipesBundle:Recipe:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getEntityManager();
        $entity = $this->getRecipeById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }
        
        if ($entity->getCreator() != $this->getAuthUser()) {
            $this->get('session')->getFlashBag()->add('notice', 'Not allowed');
            return $this->redirect($this->generateUrl('recipe_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new RecipeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('recipe_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }
 
    protected function getEntityManager() {
        return $this->getDoctrine()->getEntityManager();
    }
    
    protected function getAuthUser() {
        return $this->get('security.context')->getToken()->getUser();
    }
    
    protected function getRecipeById($id) {
        return $this->getEntityManager()->getRepository('RM2RecipesBundle:Recipe')->find($id);
    }
}
