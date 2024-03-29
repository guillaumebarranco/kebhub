<?php

namespace Kebhub\Bundle\ApiBundle\OAuth;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\OAuthServerBundle\Storage\GrantExtensionInterface;
use OAuth2\Model\IOAuth2Client;

/**
 * Play at bingo to get an access_token: May the luck be with you!
 */
class ApiKeyGrantExtension implements GrantExtensionInterface
{

    private $userRepository;

    public function __construct(ObjectRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /*
     * {@inheritdoc}
     */
    public function checkGrantExtension(IOAuth2Client $client, array $inputData, array $authHeaders)
    {
        $user = $this->userRepository->findOneByApiKey($inputData['api_key']);

        if ($user) {
            //if you need to return access token with associated user
            return array(
                'data' => $user
            );

            //if you need an anonymous user token
            return true;
        }

        return false;
    }
}