<?php

namespace Avalanche\Bundle\SitemapBundle\Sitemap;

use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;
use Avalanche\Bundle\SitemapBundle\Sitemap\Sitemap;
use Avalanche\Bundle\SitemapBundle\Entity\Url;
use Avalanche\Bundle\SitemapBundle\Sitemap\Url\Image;
use Symfony\Component\Routing\Router;

class TestProvider implements Provider {

	public function populate($sitemap)
	{
		for ($i = 0; $i < 10; $i++) {
			$url = new Url("http://poruch.net/".$i);
			//$url->setLoc("http://poruch.net/".$i);
			$url->setLastmod(new \DateTime);
			$url->setChangefreq('daily');
			$url->setPriority('0.4');

			$sitemap->add($url);
		}
		$sitemap->save();

		$sitemap->save();
	}
}
