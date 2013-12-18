<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Controller;

use AmsterdamPHP\Bundle\MeetupBundle\Service\SponsorService;
use AmsterdamPHP\Bundle\SponsorBundle\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Controller
 * @Route("/sponsors")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="amsphp_sponsors")
     * @Template()
     */
    public function indexAction()
    {
//        $sponsorService = $this->get('meetup.sponsors');
//        $sponsors       = $sponsorService->getRandomSponsors();

        /** @var SponsorRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AmsterdamPHPSponsorBundle:Sponsor');
        $sponsors = $repository->findAll();

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
