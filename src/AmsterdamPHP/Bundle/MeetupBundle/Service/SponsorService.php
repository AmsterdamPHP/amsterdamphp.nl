<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

class SponsorService extends AbstractService
{
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
                'group_urlname' => $this->group,
                'fields'        => 'sponsors',
                'only'          => 'sponsors',
            ]
        );

        // TODO fix along with bug https://github.com/rdohms/meetup-api-client/issues/8
        $groups = $groupsInfo->toArray();
        $group = array_shift($groups);
        $sponsors = $group['sponsors'];

        //Cache resource
        $this->getCache()->set($cacheKey, base64_encode(serialize($sponsors)));
        $this->getCache()->expireat($cacheKey, strtotime('+24 hours'));

        return $sponsors;
    }
}
