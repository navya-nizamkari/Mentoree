<?php

namespace Prac\MentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Prac\MentBundle\Entity\Category;
use Prac\MentBundle\Form\CategoryType;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Category controller.
 *
 */
class CategoryController extends FOSRestController implements ClassResourceInterface
{
	public function getAction($id)
	{
		//return $this->getDoctrine()->getRepository('MentBundle:Category')->find($id);
		$category= $this->get('prac.doctrine_entity_repository.category')->findOneById($id);
		
		if ($category === null) {
			return new View(null, Response::HTTP_NOT_FOUND);
		}
		
		return $category;
	}
	
	public function cgetAction()
	{
		//return $this->getDoctrine()->getRepository('MentBundle:Category')->find($id);
		$category= $this->get('prac.doctrine_entity_repository.category')->findAll();
	
		if ($category === null) {
			return new View(null, Response::HTTP_NOT_FOUND);
		}
	
		return $category;
	}
    
	
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
	
		$categories = $em->getRepository('MentBundle:Category')->findAll();
	
		return $this->render('category/index.html.twig', array(
				'categories' => $categories,
		));
	}
	
	/**
	 * Creates a new Category entity.
	 *
	 */
	public function newAction(Request $request)
	{
		$category = new Category();
		$form = $this->createForm('Prac\MentBundle\Form\CategoryType', $category);
		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
	
			return $this->redirectToRoute('category_show', array('id' => $category->getId()));
		}
	
		return $this->render('category/new.html.twig', array(
				'category' => $category,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Finds and displays a Category entity.
	 *
	 */
	public function showAction(Category $category)
	{
		$deleteForm = $this->createDeleteForm($category);
	
		return $this->render('category/show.html.twig', array(
				'category' => $category,
				'delete_form' => $deleteForm->createView(),
		));
	}
	
	/**
	 * Displays a form to edit an existing Category entity.
	 *
	 */
	public function editAction(Request $request, Category $category)
	{
		$deleteForm = $this->createDeleteForm($category);
		$editForm = $this->createForm('Prac\MentBundle\Form\CategoryType', $category);
		$editForm->handleRequest($request);
	
		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
	
			return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
		}
	
		return $this->render('category/edit.html.twig', array(
				'category' => $category,
				'edit_form' => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
		));
	}
	
	/**
	 * Deletes a Category entity.
	 *
	 */
	public function deleteAction(Request $request, Category $category)
	{
		$form = $this->createDeleteForm($category);
		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($category);
			$em->flush();
		}
	
		return $this->redirectToRoute('category_index');
	}
	
	/**
	 * Creates a form to delete a Category entity.
	 *
	 * @param Category $category The Category entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Category $category)
	{
		return $this->createFormBuilder()
		->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
		->setMethod('DELETE')
		->getForm()
		;
	}
}
