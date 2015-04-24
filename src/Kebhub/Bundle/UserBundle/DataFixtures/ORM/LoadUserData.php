<?php

namespace Kebhub\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Kebhub\Bundle\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{


    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername('client');
        $user->setEmail('client@gmail.com');
        $user->setPlainPassword('client');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_CLIENT'));
        $userManager->updateUser($user);

        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array(''));
        $client->setAllowedGrantTypes(array('http://kebhub.local/grants/api_key'));
        $client->setUser($user);
        $clientManager->updateClient($client);






        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@gmail.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $userManager->updateUser($user);

        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array(''));
        $client->setAllowedGrantTypes(array('http://kebhub.local/grants/api_key'));
        $client->setUser($user);
        $clientManager->updateClient($client);
    }
}