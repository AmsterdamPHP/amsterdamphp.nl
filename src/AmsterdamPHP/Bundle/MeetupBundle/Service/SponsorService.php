<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

use DMS\Service\Meetup\AbstractMeetupClient;
use Predis\Client;

class SponsorService
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
     * Gets a random number of sponsors
     *
     * @param $max
     * @return array
     */
    public function getRandomSponsors($max = null)
    {
        $sponsors = $this->getAllSponsors();

        shuffle($sponsors);

        return ($max == 1) ? array_shift($sponsors) : array_slice($sponsors, 0, $max ?: count($sponsors));
    }

    /**
     * Gets an array of all User Group Sponsors
     *
     * @return array|mixed
     */
    public function getAllSponsors()
    {
        $cacheKey = 'meetup.api.sponsors.all';

        //Check Sponsor Cache
        $cachedSponsors = $this->getCache()->get($cacheKey);
        if ($cachedSponsors !== null) {
            return unserialize(base64_decode($cachedSponsors));
        }

        //Get Upcoming events
        $groupsInfo = $this->api->getGroups(
            [
                'group_urlname' => 'amsterdamphp',
                'fields'        => 'sponsors',
                'only'          => 'sponsors',
            ]
        );

        // TODO fix along with bug https://github.com/rdohms/meetup-api-client/issues/8
        $groups = $groupsInfo->toArray();
        $group = array_shift($groups);
        $sponsors = $group['sponsors'];

        // Inject sponsor id to each sponsor
        foreach ($sponsors as $key => $sponsor) {
            $sluggableText = $sponsor['name'];
            $urlized = strtolower( trim( preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', iconv('UTF-8', 'ASCII//TRANSLIT', $sluggableText) ), '-' ) );
            $urlized = preg_replace("/[\/_|+ -]+/", '-', $urlized);

            $sponsors[$key]['id'] = $urlized;
        }

        //Cache resource
        $this->getCache()->set($cacheKey, base64_encode(serialize($sponsors)));
        $this->getCache()->expireat($cacheKey, strtotime('+24 hours'));

        return $sponsors;
    }

    public function getSponsor($id)
    {
        $sponsors = $this->getAllSponsors();

        foreach ($sponsors as $sponsor) {
            if ($sponsor['id'] == $id) {
                return $sponsor;
            }
        }

        throw new Exception('Sponsor not found');
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
