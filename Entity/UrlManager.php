<?php

namespace Avalanche\Bundle\SitemapBundle\Entity;

use Avalanche\Bundle\SitemapBundle\Sitemap\UrlRepositoryInterface;
use Avalanche\Bundle\SitemapBundle\Sitemap\Url;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class UrlManager implements UrlRepositoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

	protected $repository = "Avalanche\Bundle\SitemapBundle\Entity\Url";

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    function prepare() {
		$platform = $this->em->getConnection()->getDatabasePlatform();
		$tbl = 'sitemap';
		$this->em->getConnection()->executeUpdate($platform->getTruncateTableSQL($tbl, true));
    }
	
    function findOneByLoc($loc)
    {
		return $this->em->getRepository($this->repository)->findOneByLoc($loc);
    }

    function findAllOnPage($page)
    {
		return $this->em->getRepository($this->repository)
				->createQueryBuilder('s')
				->setFirstResult(UrlRepositoryInterface::PER_PAGE_LIMIT * ($page - 1))
				->setMaxResults(UrlRepositoryInterface::PER_PAGE_LIMIT)
				->getQuery()
				->getResult();
    }

    function add(Url $url) {
		$this->em->persist($url);		
    }

    function remove(Url $url) {
		$this->em->remove($url);
    }

    function pages() {
		return max(ceil(count($this->em->getRepository($this->repository)->findAll()) / UrlRepositoryInterface::PER_PAGE_LIMIT), 1);
    }

    function flush() {
		$this->em->flush();
    }

    function getLastmod($page) {
    }
}
