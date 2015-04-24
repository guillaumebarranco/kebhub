<?php 

namespace Kebhub\Bundle\DashboardBundle\Services;
use ApiKebhub\kebhub\KebhubApiGet;

/*
*	Class de Test pour l'appe au SDK ET Pour l'OpenGraph
*/

class KebhubApiService {

	public function getKebhubDatas($data_get = null) {

		// APPEL A L'API

		if($data_get != null) { // Si la requête vient de l'open graph
			$settings = array(
			    'client_id' => $data_get['client_id'],
			    'secret_id' => $data_get['secret_id'],
			    'api_key' => $data_get['api_key']
			);

			$type = $data_get['type'];
			$fields = $data_get['fields'];
			$limit = $data_get['limit'];
		} else {
			$settings = array(
			    'client_id' => "3_1aatbmz3zj1cgoksow4s0wossg8gcg8o4s8c4kc80wcg04o8wo",
			    'secret_id' => "15xyk9074ruskwkw80wck0s8c4g8k0wg488gowsc44oc8gokcw",
			    'api_key' => "73d6d6bf654c7e881664a6883ba9dadc300cff3d"
			);

			$type = 'twitter';
			$fields = 'all';
			$limit = '3';
		}

		//http://localhost:8000/oauth/v2/token?grant_type=http://kebhub.local/grants/api_key&client_id=5_1a51hozeim9wgwsckgs4wkkws8g4owck4ks8okscgossg0c4k8&client_secret=51kyotr6e5wc8cc0cswo44sswos0sgo088owcgs4kg0gswo0ss&api_key=87ba2d259e2181fda837eb664dbfd5522bc49da5

		$kebhub = new KebhubApiGet($settings);

		$access_token = $kebhub->getAccessToken();

		$url = 'http://kebhub.com/api/get/'.$type.'/'.$limit.'/'.$fields.'?access_token='.$access_token;

		$json_kebhub = $kebhub->performRequest($url, true);
		$kebhub_datas = json_decode($json_kebhub, true);

		/*
		if(isset($kebhub_datas['error_description']) && $kebhub_datas['error_description'] = "The access token provided has expired.") { // Si le précédent token est expiré
			$access_token = $kebhub->getAccessToken();

			$url = 'http://localhost:8000/app_dev.php/api/get/'.$type.'/'.$limit.'/'.$fields.'?access_token='.$access_token;
			$json_kebhub = $kebhub->performRequest($url, true);
			$kebhub_datas = json_decode($json_kebhub, true);
		}
		*/	

		//$json_kebhub = $kebhub->buildOauth($url, $requestMethod)->performRequest();

		// GESTION DES DONNEES RETOURNEES
		$datas = array();

		if($data_get != null) {
			$datas['access_token'] = $access_token;
		}

    	$k = 1;

		foreach ($kebhub_datas as $key => $status) {

			if(isset($status['id'])) {

				$datas[$k] = array();
				$datas[$k]['id'] = $status['id'];
				$datas[$k]['text'] = $status['text'];
				$datas[$k]['link'] = $status['link'];
				$datas[$k]['type'] = $status['type'];
				$datas[$k]['picture'] = $status['picture'];
				$datas[$k]['date'] = $status['date'];
				$datas[$k]['title'] = $status['title'];

				$k++;
			}
		}

		if($datas != null) {
			return $datas;
		}

		return 'no datas';
	}

}

?>