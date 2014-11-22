<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Service;

use Madcoda\Youtube;
use Predis\Client as Cache;

class YoutubeService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var string
     */
    protected $playlist;

    /**
     * Constructor
     */
    public function __construct(Cache $cache, $key, $playlist)
    {
        $this->client = new Youtube(array(
            'key' => $key,
        ));
        $this->cache = $cache;
        $this->playlist = $playlist;
    }

    /**
     * Returns the latest blog posts
     *
     * @return mixed
     */
    public function getLatestVideos()
    {
        $cacheKey = 'youtube.talks.latest';
        $videoList = $this->cache->get($cacheKey);

        if ($videoList === null){
            $videoList = base64_encode(serialize($this->client->getPlaylistItemsByPlaylistId($this->playlist)));
            $this->cache->set($cacheKey, $videoList);
            $this->cache->expireat($cacheKey, strtotime('+5 hours'));
        }

        $videoList = unserialize(base64_decode($videoList));

        return $videoList;
    }
}
