<?php

namespace RM2\RecipesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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
     * @Route("/", name="ingredient_list")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RM2RecipesBundle:Ingredient')->findAll();
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($entities, 'json');
        
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application\json');
        
        return $response;
    }
    
    /**
     * @Route("/name-like/{name}", name="ingredient_list_name_like", defaults={"name"=""})
     */
    public function filterNameLikeAction($name) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RM2RecipesBundle:Ingredient')->getIngredientsWithNameLike($name);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($entities, 'json');
        
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application\json');
        
        return $response;
    }
        
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
