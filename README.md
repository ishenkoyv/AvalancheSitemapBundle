# Requirements

This SitemapBundle is modification of Avalanche123 sitemap bundle which support generation of sitemap files with Doctrine ORM.

# Installation

Installation is general for Symfony2 bundles.
From the root directory of your Symfony2 application issue the following commands:
    $> mkdir src/Bundle/Avalanche
    $> git submodule add git@github.com:ishenkoyv/AvalancheSitemapBundle.git src/Avalanche/Bundle/SitemapBundle

This bundle will help with sitemap generation in your Symfony2 based projects.
To enable the sitemap bundle, add it to you kernel registerBundles() method:

    use Symfony\Foundation\Kernel;

    class MyKernel extends Kernel {
        ...
        public function registerBundles() {
            return array(
                ...
                new Avalanche\Bundle\SitemapBundle\AvalancheSitemapBundle(),
                ...
            );
        }
        ...

Also add path to registerNamespaces array in autoload

		$loader->registerNamespaces(array(
			...
			'Avalanche' => __DIR__.'/../src/Avalanche',
			...
		));


# Enabling the services

The second step is to enable its DependencyInjection extension in your
config.yml:

	avalanche_sitemap:
		base_url: "http://mywebsite.com/"

# Writing custom url providers for *sitemap:generate* command

The third step is to write your url providers to populate the 'sitemap' with
existing urls, e.g:

    <?php

    namespace My\ForumBundle\Sitemap;

    use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;
    use Avalanche\Bundle\SitemapBundle\Sitemap\Sitemap;
    use Avalanche\Bundle\SitemapBundle\Sitemap\Sitemap\Url;
    use Avalanche\Bundle\SitemapBundle\Sitemap\Sitemap\Url\Image;
    use Symfony\Component\Routing\Router;
    use My\ForumBundle\Document\TopicRepository;

    class ForumTopicProvider implements SitemapProvider {

        protected $topicRepository;
        protected $router;

        public function __construct(TopicRepository $topicRepository, Router $router)
        {
            $this->topicRepository = $topicRepository;
            $this->router = $router;
        }

        public function populate(Sitemap $sitemap)
        {
            foreach ($this->topicRepository->find() as $topic) {
                $url = new Url($this->router->generate('topic_view', array('id' => $topic->getId()));
                $url->setLastmod($topic->getUpdateAt());
                $url->setChangefreq('daily');
                $url->setPriority('0.4');

                foreach ($topic->getAttachedImages() as $attachedImage) {
                    $image = new Image($this->router->generate('topic_image_view', array('id' => $attachedImage->getId())));
                    $image->setCaption($attachedImage->getCaption());
                    $image->setTitle($attachedImage->getTitle());
                    $image->setLicense('http://creativecommons.org/licenses/by/3.0/legalcode');

                    $url->add($image);
                }

                $sitemap->add($url);
            }

            $sitemap->save();
        }
    }

**NOTE:** in the above example, we use router to relative urls or paths. Upon
rendering, sitemap will figure out if the url is relative and will prefix it
with current base url. If you want your urls to belong to certain domain, that might be different from the one the sitemap will be available at, make sure to use absolute urls.

And register your provider in DIC like this:

    <service id="forum.sitemap.provider" class="My\ForumBundle\ForumTopicProvider">
        <tag name="sitemap.provider" />
        <argument type="service" id="forum.document_repository.topic" />
        <argument type="service" id="router" />
    </service>

After providers are in place and registered, time to run the generation command:

    > php forum/console sitemap:generate

