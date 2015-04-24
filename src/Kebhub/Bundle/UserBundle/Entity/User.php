<?php

namespace Kebhub\Bundle\UserBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="api_key", type="string")
     */
    protected $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_name", type="string", length=255, nullable=true)
     */
    protected $twitter_name;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_id", type="string", length=255, nullable=true)
     */
    protected $instagram_id;


    public function __construct() {
    	parent::__construct();
    	$this->roles = array('ROLE_CLIENT');
        $apiKey = sha1($this->username . time());
        $this->setApiKey($apiKey);
    }


    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set twitter_name
     *
     * @param string $twitter_name
     * @return User
     */
    public function setTwitterName($twitter_name)
    {
        $this->twitter_name = $twitter_name;

        return $this;
    }

    /**
     * Get twitter_name
     *
     * @return string 
     */
    public function getTwitterName()
    {
        return $this->twitter_name;
    }

    /**
     * Set instagram_id
     *
     * @param integer $instagram_id
     * @return User
     */
    public function setInstagramId($instagram_id)
    {
        $this->instagram_id = $instagram_id;

        return $this;
    }

    /**
     * Get instagram_id
     *
     * @return integer 
     */
    public function getInstagramId()
    {
        return $this->instagram_id;
    }

}
