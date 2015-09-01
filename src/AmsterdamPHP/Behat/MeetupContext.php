<?php

namespace AmsterdamPHP\Behat;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\MinkDictionary;
use Behat\MinkExtension\Context\RawMinkContext;
use DMS\Service\Meetup\AbstractMeetupClient;
use DMS\Service\Meetup\Response\MultiResultResponse;
use Guzzle\Tests\Common\Cache\NullCacheAdapterTest;
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

    /**
     * @param $num
     * @param string $prefix
     * @return array
     */
    protected function getSponsorArray($num)
    {
        $sponsors = [];
        for ($i = 0; $i < $num; $i++) {
            $sponsors[$i] = [
                'details' => "O'Reilly is one of the largest publishers of Tech-related books",
                'image_url' => 'http://photos2.meetupstatic.com/photos/sponsor/c/f/4/6/iab120x90_1433062.jpeg',
                'redeem' => "Use DSUG discount code at O'Reilly's online store",
                'name' => "O'Reilly",
                'perk_url' => 'http://www.meetup.com/sponsor/OReilly-73300/',
                'url' => 'http://www.oreilly.com/store/',
                'info' => '40% off Print books',
            ];
        }

        return $sponsors;
    }

    /**
     * @Given /^Meetup API returns upcoming events with:$/
     */
    public function meetupApiReturnsUpcomingEventsWith(TableNode $table)
    {
        $this->defineStandardResponseSet();

        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.events.monthly_meeting.upcoming')->andReturn(base64_encode(serialize($this->getEventResponseFromTable($table))));
    }

    /**
     * @param TableNode $table
     * @return array
     */
    protected function getEventResponseFromTable($table)
    {
        $events = [];
        foreach ($table->getHash() as $row) {
            $event = [
                'visibility' => 'public',
                'status'     => 'upcoming',
                'venue'      => [
                    'name'      => $row['venue_name'],
                    'address_1' => $row['venue_addr_1'],
                    'address_2' => $row['venue_addr_2'],
                    'city'      => 'Amsterdam',
                ],
                'maybe_rsvp_count' => 3,
                'waitlist_count'   => 3,
                'yes_rsvp_count'   => 3,
                'event_url'        => 'http://meetup.com/event_url',
                'description'      => 'this is the event description, here we see speaker info and so on',
                'name'             => $row['name'],
                'time'             => (new \DateTime($row['date']))->format('u')*1000,

            ];

            if (isset($row['rsvp_limit']))     $events['rsvp_limit'] = $row['rsvp_limit'];
            if (isset($row['waitlist_count'])) $events['waitlist_count'] = $row['waitlist_count'];

            $events[] = $event;
        }

        $body = ['results' => $events, 'meta' => []];
        return new MultiResultResponse(200, ['Content-Type' => 'application/json'], json_encode($body));
    }

    protected function getEventSimResponse()
    {
        $events = [];
        foreach (range(1,2) as $row) {
            $event = [
                'visibility' => 'public',
                'status'     => 'upcoming',
                'venue'      => [
                    'name'      => $row['venue_name'],
                    'address_1' => $row['venue_addr_1'],
                    'address_2' => $row['venue_addr_2'],
                    'city'      => 'Amsterdam',
                ],
                'maybe_rsvp_count' => 3,
                'waitlist_count'   => 3,
                'yes_rsvp_count'   => 3,
                'event_url'        => 'http://meetup.com/event_url',
                'description'      => 'this is the event description, here we see speaker info and so on',
                'name'             => $row['name'],
                'time'             => (new \DateTime($row['date']))->format('u')*1000,

            ];

            if (isset($row['rsvp_limit']))     $events['rsvp_limit'] = $row['rsvp_limit'];
            if (isset($row['waitlist_count'])) $events['waitlist_count'] = $row['waitlist_count'];

            $events[] = $event;
        }

        $body = ['results' => $events, 'meta' => []];
        return new MultiResultResponse(200, ['Content-Type' => 'application/json'], json_encode($body));
    }

    public function defineStandardResponseSet()
    {
        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.events.monthly_meeting.upcoming')->andReturn(base64_encode(serialize($this->getEventSimResponse())));

        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.pool')->andReturn(serialize($this->getPhotoArray(5, 'preselection')));

        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.photos.all')->andReturn(serialize($this->getPhotoArray(10)));

        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.sponsors.all')->andReturn(serialize($this->getSponsorArray(10)));
    }

    /**
     * @Given /^Meetup API returns "([^"]*)" sponsor[s?]$/
     */
    public function meetupApiReturnsSponsors($number)
    {
        $cacheMock = $this->mocker->mockService('snc_redis.cache', Client::class);
        $cacheMock->shouldReceive('get')->with('meetup.api.sponsors.all')->andReturn(serialize($this->getSponsorArray($number)));
    }
}

