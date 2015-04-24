<?php

namespace Kebhub\Bundle\ApiBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Kebhub\Bundle\UserBundle\Entity\User",cascade={"persist"})
     */
    protected $user;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set user
     *
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     */
    public function getUser()
    {
        return $this->user;
    }
      
}