<?php

namespace Kebhub\Bundle\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Kebhub\Bundle\UserBundle\Entity\User;
use Kebhub\Bundle\DashboardBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard_homepage")
     * @Template()
     */

    public function indexAction() {

    	$user = $this->getUser();

        if ($user->getTwitterName() || $user->getInstagramId()) {

        	return array();
            
        }

        return $this->redirect($this->generateUrl('dashboard_account'));
    }
    

    /**
     * Return profile page
     *
     * @Route("/account", name="dashboard_account")
     * @Template()
     */
    
    public function accountAction(Request $request)
    {
    	$user = $this->getUser();
    	$em = $this->getDoctrine()->getEntityManager();

    	// Get random_id and secret_id's client related by his ID
        $client = $em->getRepository('KebhubApiBundle:Client')->findOneByUser($user->getId());

        // Create form to update twitter & instagram ID
        $form = $this->createFormBuilder($user)
                ->add('twitter_name', 'text', array('required' => false,'attr' => array('placeholder' => 'Twitter username', 'class' => 'form-control')))
                ->add('instagram_id', 'text', array('required' => false, 'attr' => array('placeholder' => 'Instagram username', 'class' => 'form-control')))
                ->add('Update', 'submit')
                ->getForm();
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        return array('clientApi' => $client, 'form' => $form->createView());
    }

    /**
     * Get saved posts with/without filter and limit
     *
     * @Route("/api_test/", name="api_example")
     * @Template()
     */

    public function testApiAction() {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();

        // Get random_id and secret_id's client related by his ID
        $client = $em->getRepository('KebhubApiBundle:Client')->findOneByUser($user->getId());

        return array('clientApi' => $client);
    }

}
