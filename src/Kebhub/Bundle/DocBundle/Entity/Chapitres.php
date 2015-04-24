<?php

namespace Kebhub\Bundle\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapitres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kebhub\Bundle\DocBundle\Entity\ChapitresRepository")
 */
class Chapitres
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=510)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=510)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Kebhub\Bundle\DocBundle\Entity\Articles", mappedBy="chapitre", cascade={"remove"})
    */
    private $articles;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Chapitres
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Chapitres
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get articles
     *
     */
    public function getArticles()
    {
       return $this->articles;
    }

    public function __toString()
    {
        return $this->name;
    }
}
