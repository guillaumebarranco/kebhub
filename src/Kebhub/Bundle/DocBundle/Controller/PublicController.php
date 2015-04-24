<?php

namespace Kebhub\Bundle\DocBundle\Controller;

use Kebhub\Bundle\DocBundle\Entity\Chapitres;
use Kebhub\Bundle\DocBundle\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PublicController extends Controller
{

	/**
     * @Route("/doc", name="doc_no_slug")
     * @Template("KebhubDocBundle:Public:index.html.twig")
     */
    public function noSlugAction()
    {

    	$em = $this->getDoctrine()->getEntityManager();
    	$chapitres = $em->getRepository('KebhubDocBundle:Chapitres')->findAll();
    	$chapitre = $em->getRepository('KebhubDocBundle:Chapitres')->findBy(array(), array('id' => 'ASC'), 1);

    	if ($chapitres) {

    		return array('chapitres' => $chapitres, 'thischapitre' => $chapitre);

    	}

    	throw $this->createNotFoundException('No chapters');

    }

    /**
     * @Route("/doc/{slug}", name="doc_index")
     * @Template()
     */
    public function indexAction($slug)
    {

    	$em = $this->getDoctrine()->getEntityManager();
    	$chapitres = $em->getRepository('KebhubDocBundle:Chapitres')->findAll();
    	$chapitre = $em->getRepository('KebhubDocBundle:Chapitres')->findBySlug($slug);

    	if ($chapitres) {

    		return array('chapitres' => $chapitres, 'thischapitre' => $chapitre);

    	}

    	throw $this->createNotFoundException('No chapters');

    }
}
