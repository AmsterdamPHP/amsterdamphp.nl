<?php

namespace AmsterdamPHP\Behat;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\MinkDictionary;
use Behat\MinkExtension\Context\RawMinkContext;
use DMS\Service\Meetup\AbstractMeetupClient;
use Predis\Client;
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
        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.all')->andReturn(serialize($this->getPhotoArray($number)));
    }

    /**
     * @Given /^Pre-Selection has "([^"]*)" photos$/
     */
    public function preSelectionHasPhotos($number)
    {
        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.pool')->andReturn(serialize($this->getPhotoArray($number, 'preselection')));
    }

    /**
     * @Given /^Pre-Selection is empty$/
     */
    public function preSelectionIsEmpty()
    {
        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.pool')->andReturn(null);

        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.all')->andReturn(serialize($this->getPhotoArray(10)));
    }

    /**
     * @Given /^The Image should belong to the pre-selection$/
     */
    public function theImageShouldBelongToThePreSelection()
    {
        /** @var FeatureContext $mainContext */
        $mainContext = $this->getMainContext();
        $mainContext->assertSession()->elementContains('css', '.content', '.preselection.highres.photo.jpg');
    }

    /**
     * @param $num
     * @param string $prefix
     * @return array
     */
    protected function getPhotoArray($num, $prefix = 'regular')
    {
        $photos = [];
        for ($i = 0; $i < $num; $i++) {
            $photos[$i] = [
                'photo_link'   => "$i.$prefix.photo.jpg",
                'highres_link' => "$i.$prefix.highres.photo.jpg",
            ];
        }

        return $photos;
    }
}
