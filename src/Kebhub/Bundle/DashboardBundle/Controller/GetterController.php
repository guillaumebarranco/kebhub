<?php

namespace Kebhub\Bundle\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kebhub\Bundle\DashboardBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class GetterController extends Controller
{

    /**
     * Get all saved posts.
     *
     * @Route("/get/recorded", name="getter_recorded", options={"expose"=true})
     * @Template()
     */
    public function getRecordedAction()
    {
        $posts = $this->get('kebhub.getpost')->getAllPostService('all');
        return new JsonResponse($posts);
    }


    /**
     * Get all unsaved twitter and instagram posts
     *
     * @Route("/get/stream/{filter}", name="getter_stream", options={"expose"=true})
     * @Template()
     */
    public function getStreamAction($filter)
    {

        $data = array();
        $user = $this->getUser();

        if ($user->getTwitterName() || $user->getInstagramId()) {

            $datas_twitter = array();
            $datas_instagram = array();
            $link_saved = array();
            $posts = $this->get('kebhub.getpost')->getAllPostService('all');


            if (isset($posts[0])) {
            
                foreach ($posts as $key => $post) {
                    $link_saved[] = $post['link'];
                }

            }
            // Appels au services

            if ($user->getTwitterName() && $filter == "twitter") {
                $datas_twitter = $this->get('kebhub.get_twitter_api')->getTwitterDatas($user->getTwitterName(), $link_saved);
            }
            if ($user->getInstagramId() && $filter == "instagram") {
                $datas_instagram = $this->get('kebhub.get_instagram_api')->getInstagramDatas($user->getInstagramId(), $link_saved);
            }

            $data = array_merge($datas_twitter, $datas_instagram);
            return new JsonResponse($data);    
        }
        
    }

    /**
     * Get all unsaved twitter and instagram posts
     *
     * @Route("/get/apikebhub/", name="get_api_kebhub", options={"expose"=true})
     * @Template()
     */
    public function getAPIKebhubAction(Request $request)
    {

        $data = array();

        if(null != $request->getContent()) {
            $data = $request->request->get('data');

            if(isset($data['info'])) { // Si la requête vient de l'OpenGraph
                $datas = $this->get('kebhub.get_kebhub_api')->getKebhubDatas($data);
                return new JsonResponse($datas);
            }
        }

        $datas = $this->get('kebhub.get_kebhub_api')->getKebhubDatas();

        return new JsonResponse($datas);
    }


    /**
     * Delete recorded post by ID
     *
     * @Route("/delete/recorded", name="getter_delete_recorded", options={"expose"=true})
     * @Template()
     * @Method("POST")
     */
    public function deleteRecordedAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $em = $this->getDoctrine()->getEntityManager();
        $post = $em->getRepository('KebhubDashboardBundle:Post')->find($id);

        if ($post) {

            if ($post->getUser()->getId() == $this->getUser()->getId()) {
                $em->remove($post);
                $em->flush();
            }

        }


        $posts = $this->get('kebhub.getpost')->getAllPostService('all');
        return new JsonResponse($posts);
    }


    /**
     * Delete mutliple recorded post
     *
     * @Route("/delete/multiple", name="getter_delete_multiple_recorded", options={"expose"=true})
     * @Template()
     * @Method("POST")
     */
    public function deleteMultipleRecordedAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $ids = $data->id;

        $posts = $this->get('kebhub.getpost')->deleteMutlipePost($ids, $this->getUser());

        return new JsonResponse($posts);
    }


    /**
     * add record to database
     *
     * @Route("/add/record", name="getter_add_record", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function addRecordAction(Request $request) 
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data)) {

            if($data['text'] != null || $data['title'] != null) {

                $em = $this->getDoctrine()->getManager();
                $post = new Post();

                $post->setText($data['text']);
                $post->setLink($data['link']);
                $post->setUser($this->getUser());
                $post->setType($data['type']);
                $post->setPicture($data['picture']);
                $post->setDate($data['date']);
                $post->setTitle($data['title']);

                $em->persist($post);
                $em->flush();

                return new JsonResponse();
            }
        }
    }


    /**
     * add multiple records to database
     *
     * @Route("/add/multiple", name="getter_add_multiple", options={"expose"=true})
     * @Method("POST")
     *
     */
    public function addMultipleRecordAction(Request $request) 
    {
        $data = utf8_encode($request->getContent());
        $json = json_decode($data, true);

       

        if (isset($json)) {

            $em = $this->getDoctrine()->getManager();

            foreach ($json as $value) {

                $post = new Post($value);

                $post->setText($value['text']);
                $post->setLink($value['link']);
                $post->setUser($this->getUser());
                $post->setType($value['type']);
                $post->setPicture($value['picture']);
                $post->setDate($value['date']);
                $post->setTitle($value['title']);
                $em->persist($post);

            }            
        }

        $em->flush();
        $em->clear();

        return new JsonResponse();
    }
}
