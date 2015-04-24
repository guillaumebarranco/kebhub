<?php

namespace Kebhub\Bundle\DocBundle\Twig;

class DocExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('slug', array($this, 'slugFilter')),
        );
    }

    public function slugFilter($str)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $str);

        return $slug;
    }

    public function getName()
    {
        return 'doc_extension';
    }
}