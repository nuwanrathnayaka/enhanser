<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recommendation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Recommendation controller.
 *
 * @Route("recommendation")
 */
class RecommendationController extends Controller
{
    /**
     * Lists all recommendation entities.
     *
     * @Route("/", name="recommendation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recommendations = $em->getRepository('AppBundle:Recommendation')->findAll();

        return $this->render('recommendation/index.html.twig', array(
            'recommendations' => $recommendations,
        ));
    }

    /**
     * Creates a new recommendation entity.
     *
     * @Route("/new", name="recommendation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $recommendation = new Recommendation();
        $form = $this->createForm('AppBundle\Form\RecommendationType', $recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recommendation);
            $em->flush();

            return $this->redirectToRoute('recommendation_show', array('id' => $recommendation->getId()));
        }

        return $this->render('recommendation/new.html.twig', array(
            'recommendation' => $recommendation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a recommendation entity.
     *
     * @Route("/{id}", name="recommendation_show")
     * @Method("GET")
     */
    public function showAction(Recommendation $recommendation)
    {
        $deleteForm = $this->createDeleteForm($recommendation);

        return $this->render('recommendation/show.html.twig', array(
            'recommendation' => $recommendation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing recommendation entity.
     *
     * @Route("/{id}/edit", name="recommendation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recommendation $recommendation)
    {
        $deleteForm = $this->createDeleteForm($recommendation);
        $editForm = $this->createForm('AppBundle\Form\RecommendationType', $recommendation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recommendation_edit', array('id' => $recommendation->getId()));
        }

        return $this->render('recommendation/edit.html.twig', array(
            'recommendation' => $recommendation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a recommendation entity.
     *
     * @Route("/{id}", name="recommendation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Recommendation $recommendation)
    {
        $form = $this->createDeleteForm($recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recommendation);
            $em->flush();
        }

        return $this->redirectToRoute('recommendation_index');
    }

    /**
     * Creates a form to delete a recommendation entity.
     *
     * @param Recommendation $recommendation The recommendation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recommendation $recommendation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recommendation_delete', array('id' => $recommendation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
