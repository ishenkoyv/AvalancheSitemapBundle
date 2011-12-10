<?php

namespace Avalanche\Bundle\SitemapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Avalanche\Bundle\SitemapBundle\Sitemap;

class SitemapController
{
    private $sitemap;
    private $templating;

    public function __construct(Sitemap $sitemap, EngineInterface $templating)
    {
        $this->sitemap    = $sitemap;
        $this->templating = $templating;
    }

    public function sitemap($page = 1)
    {
        $this->sitemap->setPage($page);

        return $this->templating->renderResponse('AvalancheSitemapBundle:Sitemap:sitemap.twig.xml', array(
            'sitemap' => $this->sitemap
        ));
    }

    public function siteindex()
    {
        return $this->templating->renderResponse('AvalancheSitemapBundle:Sitemap:siteindex.twig.xml', array(
            'pages'   => $this->sitemap->pages(),
            'sitemap' => $this->sitemap,
        ));
    }
}
