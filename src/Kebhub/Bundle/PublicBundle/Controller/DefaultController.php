<?php

namespace Kebhub\Bundle\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="public_homepage")
     * @Template()
     */
    public function indexAction()
    {	
    	$user = $this->getUser();
    	if( $user ){
    		
    	}
    	

        return array();
    }
}
