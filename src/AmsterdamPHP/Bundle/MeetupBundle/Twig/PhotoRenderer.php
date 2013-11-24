<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Twig;

use AmsterdamPHP\Bundle\MeetupBundle\Service\PhotoService;

class PhotoRenderer extends \Twig_Extension
{
    /**
     * @var PhotoService
     */
    protected $photoService;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @param $photoService
     */
    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getMeetupPhotoUrl', array($this, 'getMeetupPhotoUrl')),
            new \Twig_SimpleFunction('getMeetupPhotoCredits', array($this, 'getMeetupPhotoCredits')),
            new \Twig_SimpleFunction('displayPhotoBar', array($this, 'displayPhotoBar'), array('needs_environment' => true, 'is_safe' => array('html', 'javascript'))),
        );
    }

    /**
     * Renders a photo bar
     *
     * @param \Twig_Environment $twig
     * @return string
     */
    public function displayPhotoBar(\Twig_Environment $twig, $number)
    {
        $data = array(
            'photos' => $this->photoService->getRandomPhotos($number)
        );

        return $twig->render('AmsterdamPHPMeetupBundle:Twig:photo-bar.html.twig', $data);
    }

    /**
     * @param $photo
     * @param string $desiredResolution
     * @return mixed
     */
    public function getMeetupPhotoUrl($photo, $desiredResolution = 'normal')
    {
        $hasHighRes = (isset($photo['highres_link']));
        $hasThumb   = (isset($photo['thumb_link']));

        if ($desiredResolution == 'highres' && $hasHighRes) {
            return $photo['highres_link'];
        }

        if ($desiredResolution == 'thumb' && $hasThumb) {
            return $photo['thumb_link'];
        }

        return $photo['photo_link'];
    }

    public function getMeetupPhotoCredits($photo)
    {
        if (! isset($photo['member']['name'])) {
            return '';
        }

        return $photo['member']['name'];
    }

    public function getName()
    {
        return 'meetup_photo_renderer';
    }

}
