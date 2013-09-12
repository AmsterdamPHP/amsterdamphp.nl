<?php

namespace AmsterdamPHP\Behat;

use Behat\Behat\Context\BehatContext;
use PSS\Behat\Symfony2MockerExtension\Context\ServiceMockerAwareInterface;
use PSS\Behat\Symfony2MockerExtension\ServiceMocker;

class MeetupContext extends BehatContext implements ServiceMockerAwareInterface
{
    /**
     * @var ServiceMocker $mocker
     */
    private $mocker = null;

    /**
     * @param \PSS\Behat\Symfony2MockerExtension\ServiceMocker $mocker
     * @param ServiceMocker $mocker
     * @return null|void
     */
    public function setServiceMocker(ServiceMocker $mocker)
    {
        $this->mocker = $mocker;
    }

    /**
     * @Given /^Meetup API returns "([^"]*)" photos$/
     */
    public function meetupApiReturnsPhotos($number)
    {
        $this->mocker->mockService('meetup.photos', 'AmsterdamPHP\Bundle\MeetupBundle\Service\PhotoService[getAllPhotos]')
            ->shouldReceive('getAllPhotos')
            ->once()
            ->andReturn($this->getPhotoArray($number));
    }

    protected function getPhotoArray($num)
    {
        $photos = array();
        for ($i = 0; $i < $num; $i++) {
            $photos[$i] = array(
                'photo_link' => $i . 'photo.jpg'
            );
        }

        return $photos;
    }
}
