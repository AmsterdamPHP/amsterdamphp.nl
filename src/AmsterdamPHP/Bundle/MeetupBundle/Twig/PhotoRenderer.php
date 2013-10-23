<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Twig;

class PhotoRenderer extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getMeetupPhotoUrl', array($this, 'getMeetupPhotoUrl')),
            new \Twig_SimpleFunction('getMeetupPhotoCredits', array($this, 'getMeetupPhotoCredits')),
        );
    }

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
        return $photo['member']['name'];
    }

    public function getName()
    {
        return 'meetup_photo_renderer';
    }

}
