<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $photoService = $this->get('meetup.photos');
        $headerPhoto  = $photoService->getRandomPhotosFromPool(1);

        $eventService = $this->get('meetup.events');
        $nextEvents   = $eventService->getUpcomingEvents(true)->toArray();
        $pastEvents   = $eventService->getPastEvents(true)->toArray();

        $blogService = $this->get('amsterdamphp_site.integration.blog');
        $posts       = $blogService->getLatestBlogPosts();

        return [
            'header_photo' => $headerPhoto,
            'next_event'   => array_shift($nextEvents),
            'past_events'  => array_splice($pastEvents, 0, 2),
            'blog_posts'   => $posts,
        ];
    }
}
