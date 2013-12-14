<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Controller;

use AmsterdamPHP\Bundle\MeetupBundle\Service\SponsorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SponsorController
 *
 * @package AmsterdamPHP\Bundle\SiteBundle\Controller
 * @Route("/sponsors")
 */
class SponsorController extends Controller
{
    /**
     * @Route("/", name="amsphp_sponsors")
     * @Template()
     */
    public function indexAction()
    {
        $sponsorService = $this->get('meetup.sponsors');
        $sponsors       = $sponsorService->getRandomSponsors();

        return [
            'sponsors'  => $sponsors
        ];
    }

    /**
     * @Route("/{id}", name="amsphp_sponsor")
     * @Template()
     */
    public function detailAction($id)
    {
        /** @var SponsorService $sponsorService */
        $sponsorService = $this->get('meetup.sponsors');
        $sponsor        = $sponsorService->getSponsor($id);

        return [
            'sponsor'   => $sponsor,
        ];
    }
} 