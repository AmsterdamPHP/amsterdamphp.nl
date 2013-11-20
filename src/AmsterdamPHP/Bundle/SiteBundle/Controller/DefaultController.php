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
        $headerPhoto = $photoService->getRandomPhotosFromPool(1);

        $eventService = $this->get('meetup.events');
        $nextEvents   = $eventService->getUpcomingEvents(true)->toArray();
        $pastEvents   = $eventService->getPastEvents(true)->toArray();

        return [
            'header_photo' => $headerPhoto,
            'next_event'   => array_shift($nextEvents),
            'past_events'  => array_splice($pastEvents, 0, 2),
        ];
    }
}
