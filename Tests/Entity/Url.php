<?php

namespace Avalanche\Bundle\SitemapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Avalanche\Bundle\SitemapBundle\Sitemap\Url as BaseUrl;

/**
 * @ORM\Table(name="sitemap")
 * @ORM\Entity(repositoryClass="Avalanche\Bundle\SitemapBundle\Entity\Repository\UrlRepository")
 */
class Url extends BaseUrl 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique="true")
     */
	protected $loc;

    /**
     * @ORM\Column(type="date")
     */
	protected $lastmod;

    /**
     * @ORM\Column(type="string")
     */
	protected $changefreq;

    /**
     * @ORM\Column(type="string")
     */
	protected $priority;
}
