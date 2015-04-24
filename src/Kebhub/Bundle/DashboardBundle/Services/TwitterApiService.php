<?php 

namespace Kebhub\Bundle\DashboardBundle\Services;
use Twitter\twitter\TwitterAPIExchange;

class TwitterApiService {

	public function getTwitterDatas($twitter_name, $link_saved) {

		// APPEL A L'API

		$settings = array(
		    'oauth_access_token' => "2538080443-GozX3u7rLh5EO8cfbBuLFNxI9jTwqZIx2GgY6yP",
		    'oauth_access_token_secret' => "gfvwyItUpcnXVQhxgHxvINsCNOlmgw2vxPc81yeOhsdnF",
		    'consumer_key' => "p0gzW0PRw9XlJnZrFCEncNx0P",
		    'consumer_secret' => "x1pFnIFYal9qvscgAs1THe7tzXGs1p5I7BMjl8bSwXNFtWOux5"
		);

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='.$twitter_name;
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange($settings);

		$json_twitter = $twitter->setGetfield($getfield)
			->buildOauth($url, $requestMethod)
			->performRequest();

		$twitter_datas = json_decode($json_twitter, true);

		// GESTION DES DONNEES RETOURNEES
		$datas = array();

		if (isset($twitter_datas[0]['user']['screen_name'])) {

			$datas['twitter'][0] = $twitter_datas[0]['user']['screen_name'];
	    	$t = 1;

			foreach ($twitter_datas as $key => $status) {
				if(empty($status['retweeted_status']) && $status['in_reply_to_status_id'] === null) {

					if(!in_array('https://twitter.com/'.$twitter_name.'/status/'.$status['id_str'], $link_saved)) { 
						// Filter by posts saved

						$datas['twitter'][$t] = array();
						$datas['twitter'][$t]['text'] = $status['text'];
						$datas['twitter'][$t]['link'] = 'https://twitter.com/'.$twitter_name.'/status/'.$status['id_str'];

						// Pour être sur, une fois le lien sauvegardé pour un tweet, on le met dans le tableau pour qu'il ne puisse pas réapparaitre
						$link_saved[] = $datas['twitter'][$t]['link'];
						$t++;
					}
				}
			}
		}

		return $datas;
	}

}

?>