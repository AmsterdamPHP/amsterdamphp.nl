<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ContactController
 *
 * @package AmsterdamPHP\Bundle\SiteBundle\Controller
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/", name="amsphp_contact")
     * @Route("/", name="amsphp_be_a_speaker")
     * @Route("/sponsor-us", name="amsphp_be_a_sponsor", defaults={"sponsor"=true})
     * @Template()
     */
    public function indexAction($sponsor = false)
    {

        $form = $this->createForm('amsterdamphp_site_contact_type');
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {

            $contactService = $this->get('amsterdamphp_site.email.contact');
            $contactService->sendContact($form->getData());

            $this->get('braincrafted_bootstrap.flash')->success(
                'Thank you for getting in touch, we will reply as soon as possible.'
            );

            return $this->redirect($this->generateUrl('amsphp_contact'));
        }

        return [
            'form'            => $form->createView(),
            'sponsor_context' => $sponsor
        ];
    }
}
