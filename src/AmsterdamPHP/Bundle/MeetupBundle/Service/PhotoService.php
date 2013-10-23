<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

use DMS\Service\Meetup\AbstractMeetupClient;
use Predis\Client;

class PhotoService
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
     * Gets a random number of photos
     * 1
     * @param $max
     * @return array
     */
    public function getRandomPhotos($max)
    {
        $photos = $this->getAllPhotos();

        shuffle($photos);

        return array_slice($photos, 0, $max);
    }

    public function getRandomPhotosFromPool($pool, $max, $resolution = 'high')
    {
    }

    public function storePhotoPool($id, $entries)
    {

    }

    /**
     * Gets an array of all User Group Photos
     * @return array|mixed
     */
    public function getAllPhotos()
    {
        $cacheKey = 'meetup.api.photos.all';

        //Check Photo Cache
        $cachedPhotos = $this->cache->get($cacheKey);
        if ($cachedPhotos !== null){
            return unserialize($cachedPhotos);
        }

        $metadata = array();
        $allPhotos = array();

        while(!isset($metadata['total_count']) || $metadata['total_count'] > count($allPhotos)) {

            //Iterate getting all photos
            $photos = $this->api->getPhotos(array('group_urlname' => 'amsterdamphp'));

            $allPhotos = array_merge($allPhotos, $photos->getData());
            $metadata = $photos->getMetadata();
        }

        //Cache resource
        $this->cache->set($cacheKey, serialize($allPhotos));
        $this->cache->expireat($cacheKey, strtotime('+24 hours'));

        return $allPhotos;
    }
}
