<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

use DMS\Service\Meetup\Response\MultiResultResponse;

class EventService extends AbstractService
{
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
                'group_urlname' => $this->group,
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

    protected function getEventAttendance($eventId)
    {
        $cacheKey = "meetup.api.attendance.event.$eventId";

        //Check Photo Cache
        $cachedEvents = $this->getCache()->get($cacheKey);
        if ($cachedEvents !== null){
            $attendance = unserialize(base64_decode($cachedEvents));
            return $attendance;
        }

        //Get Upcoming events
        $attendance = $this->api->getAttendance(
            [
                'urlname' => $this->group,
                'id'      => $eventId,
                'filter'  => 'attended'
            ]
        );

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
                'group_urlname' => $this->group,
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
}
