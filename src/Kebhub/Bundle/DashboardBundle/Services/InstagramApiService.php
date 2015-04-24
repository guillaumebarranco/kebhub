<?php 

namespace Kebhub\Bundle\DashboardBundle\Services;

class InstagramApiService {

    protected $instagramClientId;

    public function __construct($instagramClientId) 
    {
        $this->instagramClientId = $instagramClientId;
    }

	public function getInstagramDatas($instagramUser, $link_saved) 
    {

        if ($instagramUser) {
		// APPEL A L'API

            $url = 'https://api.instagram.com/v1/users/search?q='.$instagramUser.'&client_id='.$this->instagramClientId;
            $get = file_get_contents($url);
            $json = json_decode($get);
            $datas = array();

            foreach($json->data as $user)
            { 
                if(strtolower($user->username) == strtolower($instagramUser)) {
                    $userId = $user->id;
                }       
            }

            if (isset($userId)) {

                $endpoint = 'https://api.instagram.com/v1/users/'.$userId.'/media/recent?client_id='.$this->instagramClientId;

                $curl = curl_init($endpoint);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $json = curl_exec($curl);

                

                $insta_datas = json_decode($json, true);

                // GESTION DES DONNEES RETOURNEES

                if ($insta_datas['data'][0]['user']['username']) {

                    $datas['instagram'][0] = $insta_datas['data'][0]['user']['username'];
                    $k = 1;

                    foreach ($insta_datas as $key => $insta) {
                    	foreach ($insta as $key => $the_data) {
                    		if($the_data['images']['standard_resolution'] != null && !in_array($the_data['link'], $link_saved)) {
                    			$datas['instagram'][$k] = array();
                    			$datas['instagram'][$k]['picture'] = $the_data['images']['standard_resolution']['url'];
                    			$datas['instagram'][$k]['link'] = $the_data['link'];
                    			$datas['instagram'][$k]['title'] = $the_data['caption']['text'];

                    			// Pour être sur, une fois le lien sauvegardé pour un post insta, on le met dans le tableau pour qu'il ne puisse pas réapparaitre
                    			$link_saved[] = $datas['instagram'][$k]['link'];
                    			$k++;
                    		}
                    	}
                    }
                }

                return $datas;
            }
            return $datas;
        }
	}

	
}

?>