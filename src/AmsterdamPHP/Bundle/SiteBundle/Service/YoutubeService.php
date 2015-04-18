<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Service;

use Madcoda\Youtube;
use Predis\Client as Cache;

class YoutubeService
{
    const CACHE_TTL = '+24 hours';
    
    /**
     * @var Youtube
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
    public function __construct(Cache $cache, Youtube $client, $playlist)
    {
        $this->client = $client;
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
            $this->cache->expireat($cacheKey, strtotime(self::CACHE_TTL));
        }

        $videoList = unserialize(base64_decode($videoList));

        return $videoList;
    }
}
