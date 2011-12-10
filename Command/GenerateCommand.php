<?php

namespace Avalanche\Bundle\SitemapBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;

class GenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sitemap:generate')
            ->setDescription('Generate sitemap, using its data providers.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$sitemapDir = $this->getContainer()->getParameter('kernel.root_dir') . '/../web/sitemap/';

        $sitemap = $this->getContainer()->get('sitemap');
        $sitemap->prepare();

        $this->getContainer()->get('sitemap.provider.chain')->populate($sitemap);


		@file_put_contents($sitemapDir. 'sitemap.xml', $this->getContainer()->get('sitemap.controller')->siteindex()->getContent());

		$pagesCount = $sitemap->pages();
		for ($i = 1; $i <= $pagesCount; $i++) {
			@file_put_contents($sitemapDir. 'sitemap' . $i . '.xml', $this->getContainer()->get('sitemap.controller')->sitemap($i)->getContent());
		}

        $output->write('<info>Sitemap was sucessfully populated!</info>', true);
    }
}
