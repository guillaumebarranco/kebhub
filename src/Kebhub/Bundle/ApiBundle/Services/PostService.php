<?php

namespace Kebhub\Bundle\ApiBundle\Services;

class PostService {

    protected $repositoryPost;
    protected $securityService;

    public function __construct($repositoryPost, $securityService)
    {
        $this->repositoryPost = $repositoryPost;
        $this->securityService = $securityService;
    }

    public function getAllPostService($filter, $limit = NULL, $chosen_datas = 'all')
    {

        $user = $this->securityService->getToken()->getUser();
        $responseArray = array();

        if ($user) {

            // FILTER
            if ($filter == 'all') {
                $posts = $this->repositoryPost->getAllByUser($user);
            }
            elseif ($filter == 'twitter'){
                $posts = $this->repositoryPost->getTwitterPost($user);
            }
            elseif ($filter == 'instagram'){
                $posts = $this->repositoryPost->getInstagramPost($user);
            }

            // LIMIT
            if (!$limit && $posts) {
                $limit = count($posts);
            }

            if ($posts) {

                $i = 0;
                $array_datas = explode ( ',' , $chosen_datas);

                foreach ($posts as $post) {
                    if ($i < $limit) {

                        if($chosen_datas == 'all') {

                            array_push($responseArray, array(
                                'id' => $post->getId(),
                                'text' => $post->getText(), 
                                'link' => $post->getLink(),
                                'type' => $post->getType(),
                                'picture' => $post->getPicture(),
                                'date' => $post->getDate(),
                                'title' => $post->getTitle()
                            ));

                        } else {
                            $responseArray[$i] = array();

                            if(in_array('text', $array_datas)) {
                                $responseArray[$i]['text'] = $post->getText();
                            }
                            if(in_array('link', $array_datas)) {
                                 $responseArray[$i]['link'] = $post->getLink();
                            }
                            if(in_array('type', $array_datas)) {
                                 $responseArray[$i]['type'] = $post->getType();
                            }
                            if(in_array('picture', $array_datas)) {
                                 $responseArray[$i]['picture'] = $post->getPicture();
                            }
                            if(in_array('date', $array_datas)) {
                                 $responseArray[$i]['date'] = $post->getDate();
                            }
                            if(in_array('title', $array_datas)) {
                                $responseArray[$i]['title'] = $post->getTitle();
                            }
                        }
                        
                        $i++;
                    }
                }
                return $responseArray;
            }

            return array();
        }
        return array('message' => 'User is not identified');

    }


    public function deleteMutlipePost($ids, $user)
    {

        $posts = $this->repositoryPost->deleteMutlipePost($ids, $user);
        return $posts;

    }   
}