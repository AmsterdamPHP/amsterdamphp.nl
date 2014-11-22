<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

use DMS\Service\Meetup\AbstractMeetupClient;
use Predis\Client;

class AbstractService {
    /**
     * @var \DMS\Service\Meetup\AbstractMeetupClient
     */
    protected $api;

    /**
     * @var Client
     */
    protected $cache;

    /**
     * @var string
     */
    protected $group;

    /**
     * @param AbstractMeetupClient $api
     * @param \Predis\Client $cache
     * @param string $group path to group on meetup.com
     */
    public function __construct(AbstractMeetupClient $api, Client $cache, $group)
    {
        $this->api   = $api;
        $this->cache = $cache;
        $this->group = $group;
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
