<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Controller;

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

        $photos = $photoService->getRandomPhotos(5);

        return array(
            'photos' => $photos
        );
    }
}
