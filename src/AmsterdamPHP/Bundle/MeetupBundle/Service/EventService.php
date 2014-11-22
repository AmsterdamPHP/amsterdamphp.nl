<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

use DMS\Service\Meetup\AbstractMeetupClient;
use DMS\Service\Meetup\Response\MultiResultResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Guzzle\Http\Exception\BadResponseException;
use Predis\Client;

class EventService
{
    /**
     * @var \DMS\Service\Meetup\AbstractMeetupClient
     */
    protected $api;

    /**
     * @var Client
     */
    protected $cache;

    /**
     * @param AbstractMeetupClient $api
     * @param \Predis\Client $cache
     */
    public function __construct(AbstractMeetupClient $api, Client $cache)
    {
        $this->api   = $api;
        $this->cache = $cache;
    }

    /**
     * Gets an array of all User Group Photos
     *
     * @param bool $onlyMonthlyMeeting
     * @return array|mixed
     */
    public function getUpcomingEvents($onlyMonthlyMeeting = false)
    {
        $cacheKey = $onlyMonthlyMeeting? 'meetup.api.events.monthly_meeting.upcoming':'meetup.api.events.upcoming';
        $filter = $onlyMonthlyMeeting
            ? function ($event) { return ($event['name'] !== "Geeks & drinks"); }
            : function () {return true;};

        //Check Photo Cache
        $cachedEvents = $this->getCache()->get($cacheKey);
        if ($cachedEvents !== null){
            $events = unserialize(base64_decode($cachedEvents));
            return $events->filter($filter);
        }

        //Get Upcoming events
        $events = $this->api->getEvents(
            [
                'group_urlname' => 'amsterdamphp',
                'status'        => 'upcoming',
                'text_format'   => 'plain'
            ]
        );

        $events = $events->filter($filter);

        //Cache resource
        $this->getCache()->set($cacheKey, base64_encode(serialize($events)));
        $this->getCache()->expireat($cacheKey, strtotime('+5 hours'));

        return $events;
    }

    /**
     * Get the attendees for an event. Cache the result and return as an array.
     * If the API call fails, return an empty array and don't cache.
     *
     * @param   int $eventId
     * @return  array
     */
    protected function getEventAttendance($eventId)
    {
        $cacheKey = "meetup.api.attendance.event.$eventId";

        //Check Photo Cache
        $cachedEvents = $this->getCache()->get($cacheKey);
        if ($cachedEvents !== null){
            $attendance = unserialize(base64_decode($cachedEvents));
            return $attendance;
        }

        //Get Upcoming events attendence. If the API key you use is not authorized, skip caching the list.
        try
        {
            $attendance = $this->api->getAttendance(
                [
                    'urlname' => 'amsterdamphp',
                    'id'      => $eventId,
                    'filter'  => 'attended'
                ]
            );
        } catch(BadResponseException $e)
        {
            return array();
        }

        //Cache resource
        $this->getCache()->set($cacheKey, base64_encode(serialize($attendance)));
        $this->getCache()->expireat($cacheKey, strtotime('+5 hours'));

        return $attendance;
    }

    /**
     * Get Past Events
     *
     * @param int $max
     * @param bool $onlyMonthlyMeeting
     * @return mixed|MultiResultResponse
     */
    public function getPastEvents($onlyMonthlyMeeting = false)
    {
        $cacheKey = $onlyMonthlyMeeting? 'meetup.api.events.monthly_meeting.past':'meetup.api.events.past';
        $filter = $onlyMonthlyMeeting
            ? function ($event) { return ($event['name'] !== "Geeks & drinks"); }
            : function () {return true;};
        $map = function ($event) {
            $event['attendance'] = $this->getEventAttendance($event['id']);
            return $event;
        };

        //Check Photo Cache
        $cachedEvents = $this->getCache()->get($cacheKey);
        if ($cachedEvents !== null){
            $events = unserialize(base64_decode($cachedEvents));
            $events = $events->filter($filter);
            return $events->map($map);
        }

        //Get Upcoming events
        $events = $this->api->getEvents(
            [
                'group_urlname' => 'amsterdamphp',
                'status'        => 'past',
                'text_format'   => 'plain',
                'desc'          => 'true'
            ]
        );

        $events = $events->filter($filter);
        $events = $events->map($map);

        //Cache resource
        $this->getCache()->set($cacheKey, base64_encode(serialize($events)));
        $this->getCache()->expireat($cacheKey, strtotime('+5 hours'));

        return $events;
    }

    /**
     * @return \DMS\Service\Meetup\AbstractMeetupClient
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return \Predis\Client
     */
    public function getCache()
    {
        return $this->cache;
    }

}
