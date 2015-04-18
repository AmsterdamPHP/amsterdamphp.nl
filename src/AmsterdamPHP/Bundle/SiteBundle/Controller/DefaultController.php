<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $photoService = $this->get('meetup.photos');
        $headerPhoto  = $photoService->getRandomPhotosFromPool(1);

        $sponsorService = $this->get('meetup.sponsors');
        $sponsors       = $sponsorService->getRandomSponsors();

        $eventService = $this->get('meetup.events');
        $nextEvents   = $eventService->getUpcomingEvents(true)->toArray();
        $pastEvents   = $eventService->getPastEvents(true)->toArray();

        $blogService = $this->get('amsterdamphp_site.integration.blog');
        $posts       = $blogService->getLatestBlogPosts();

        $youtubeService = $this->get('amsterdamphp_site.integration.youtube');
        $videos         = $youtubeService->getLatestVideos();

        return [
            'header_photo' => $headerPhoto,
            'next_event'   => array_shift($nextEvents),
            'past_events'  => array_splice($pastEvents, 0, 2),
            'blog_posts'   => $posts,
            'sponsors'     => $sponsors,
            'videos'       => $videos,
        ];
    }

    /**
     * @Route("/code-of-conduct", name="code-of-conduct")
     * @Template()
     */
    public function codeOfConductAction()
    {
        return [];
    }
}
