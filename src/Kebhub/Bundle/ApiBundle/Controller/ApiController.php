<?php

namespace Kebhub\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kebhub\Bundle\DashboardBundle\Entity\Post;

class ApiController extends Controller
{

    /**
     * Get user information
     *
     * @Route("/api/get/user", name="api_get_user")
     * @Template()
     */
    public function userAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user) {
            return new JsonResponse(array(
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'apiKey' => $user->getApiKey()
            ));
        }

        return new JsonResponse(array(
            'message' => 'User is not identified'
        ));
    }

//localhost:8000/oauth/v2/token?grant_type=http://kebhub.local/grants/api_key&client_id=3_5pbqurh3dt448sck0sg84gscog80ck848go80s4w4oockwckwo&client_secret=w0koulr9w9c8so0osssw88kcckc8ck8ko0swsokkgw0kgcg0k&api_key=a6e3da8071f1a8a8d19c07b8af34bae297998648

    /**
     * Get saved posts with/without filter and limit
     *
     * @Route("/api/get/{filter}/{limit}/{chosen_datas}", name="api_get_user")
     * @Template()
     */
    public function getpostAction($filter, $limit = NULL, $chosen_datas = 'all')
    {
        $posts = $this->get('kebhub.getpost')->getAllPostService($filter, $limit, $chosen_datas);

        return new JsonResponse($posts);
    }
}