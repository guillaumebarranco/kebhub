<?php

namespace Kebhub\Bundle\DocBundle\Controller;

use Kebhub\Bundle\DocBundle\Entity\Chapitres;
use Kebhub\Bundle\DocBundle\Entity\Articles;
use Kebhub\Bundle\DocBundle\Form\ChapitresType;
use Kebhub\Bundle\DocBundle\Form\ArticlesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $chapitres = $em->getRepository('KebhubDocBundle:Chapitres')->findAll();

        return array('chapitres' => $chapitres);
    }


    /**
	 * Method for generate formulaire and persist datas to Chapitres Entity
     *
     * @Route("/admin/create/chapitre", name="admin_create_chapitre")
     * @Template()
     */
    public function createChapitresAction(Request $request)
    {
    	$chapitre = new Chapitres;
		$form = $this->get('form.factory')->create(new ChapitresType, $chapitre);

		$form->handleRequest($request);

		if ($form->isValid()) {

	        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $chapitre->getName());

			$em = $this->getDoctrine()->getEntityManager();
			$data = $form->getData();
			$chapitre->setSlug($slug);
			$em->persist($data);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_index'));

		}

        return array('form' => $form->createView());
    }


    /**
	 * Method for generate and persist datas to Articles Entity
     *
     * @Route("/admin/create/article", name="admin_create_article")
     * @Template()
     */
    public function createArticlesAction(Request $request)
    {
    	$article = new Articles;
		$form = $this->get('form.factory')->create(new ArticlesType, $article);

		$form->handleRequest($request);

		if ($form->isValid()) {

			$em = $this->getDoctrine()->getEntityManager();
			$data = $form->getData();
			$em->persist($data);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_index'));

		}

        return array('form' => $form->createView());
    }


    /**
     * Delete chapters and his related articles
     *
     * @Route("/admin/delete/chapitre/{id}", name="admin_delete_chapters")
     * @Template()
     */
    public function deleteChaptersAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $chapitre = $em->getRepository('KebhubDocBundle:Chapitres')->find($id);

        if ($chapitre) {

	        $em->remove($chapitre);
	        $em->flush();

        }

        return $this->redirect($this->generateUrl('admin_index'));
    }


    /**
     * Delete only article given
     *
     * @Route("/admin/delete/article/{id}", name="admin_delete_articles")
     * @Template()
     */
    public function deleteArticlesAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $article = $em->getRepository('KebhubDocBundle:Articles')->find($id);

        if ($article) {

	        $em->remove($article);
	        $em->flush();

        }

        return $this->redirect($this->generateUrl('admin_index'));
    }


    /**
	 * Method for update Chapters
     *
     * @Route("/admin/update/chapitre/{id}", name="admin_update_chapters")
     * @Template("KebhubDocBundle:Admin:createChapitres.html.twig")
     */
    public function updateChapitresAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $chapitre = $em->getRepository('KebhubDocBundle:Chapitres')->find($id);

        if ($chapitre) {

        	$form = $this->get('form.factory')->create(new ChapitresType, $chapitre);
			$form->handleRequest($request);

			if ($form->isValid()) {
				$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $chapitre->getName());
				
				$em = $this->getDoctrine()->getEntityManager();
				$data = $form->getData();
				$chapitre->setSlug($slug);
				$em->persist($data);
				$em->flush();

				return $this->redirect($this->generateUrl('admin_index'));

			}

		}

        return array('form' => $form->createView());
    }


    /**
	 * Method for update Articles
     *
     * @Route("/admin/update/articles/{id}", name="admin_update_articles")
     * @Template("KebhubDocBundle:Admin:createArticles.html.twig")
     */
    public function updateArticlesAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $article = $em->getRepository('KebhubDocBundle:Articles')->find($id);

        if ($article) {

        	$form = $this->get('form.factory')->create(new ArticlesType, $article);
			$form->handleRequest($request);

			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();
				$data = $form->getData();
				$em->persist($data);
				$em->flush();

				return $this->redirect($this->generateUrl('admin_index'));

			}

		}

        return array('form' => $form->createView());
    }

}
