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
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {

        $form = $this->createForm('amsterdamphp_site_contact_type');

        return [
            'form' => $form->createView()
        ];
    }
}
