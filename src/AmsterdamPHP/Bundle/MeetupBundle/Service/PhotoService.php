<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Service;

class PhotoService extends AbstractService
{
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

        return ($max == 1)? array_shift($photos) : array_slice($photos, 0, $max);
    }

    /**
     * Gets random photos from a pool of previously selected photos
     *
     * @param $max
     * @return array
     */
    public function getRandomPhotosFromPool($max)
    {
        $pool = $this->getAllPoolPhotos();

        shuffle($pool);

        return ($max == 1)? array_shift($pool) : array_slice($pool, 0, $max);
    }

    /**
     * Stores a photo in the pool of photos
     *
     * @param $id
     * @param $entries
     */
    public function storePhotoPool($id, $entries)
    {
        //TODO Implement this as part of ticket #21
    }

    /**
     * Gets all the Photos in the pool
     * Falls back to All Photos
     *
     * @param bool $fallback
     * @return array|mixed
     */
    public function getAllPoolPhotos($fallback = true)
    {
        $cacheKey = 'meetup.api.photos.pool';
        $pool = $this->getCache()->get($cacheKey);

        if ($pool !== null) {
            $pool = unserialize($pool);
        }

        if ($pool === null && $fallback) {
            $pool = $this->getAllPhotos();
        }

        return $pool;
    }

    /**
     * Gets an array of all User Group Photos
     * @return array|mixed
     */
    public function getAllPhotos()
    {
        $cacheKey = 'meetup.api.photos.all';

        //Check Photo Cache
        $cachedPhotos = $this->getCache()->get($cacheKey);
        if ($cachedPhotos !== null){
            return unserialize($cachedPhotos);
        }

        $metadata = array();
        $allPhotos = array();

        while(!isset($metadata['total_count']) || $metadata['total_count'] > count($allPhotos)) {

            //Iterate getting all photos
            $photos = $this->api->getPhotos(
                [
                    'group_urlname' => $this->group,
                ]
            );

            $allPhotos = array_merge($allPhotos, $photos->getData());
            $metadata = $photos->getMetadata();
        }

        //Cache resource
        $this->getCache()->set($cacheKey, serialize($allPhotos));
        $this->getCache()->expireat($cacheKey, strtotime('+24 hours'));

        return $allPhotos;
    }
}
