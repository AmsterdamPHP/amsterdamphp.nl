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


        return [
            'header_photo' => $headerPhoto
        ];
    }
}
