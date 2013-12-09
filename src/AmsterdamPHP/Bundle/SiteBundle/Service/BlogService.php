<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Service;

use Guzzle\Http\Client;
use Predis\Client as Cache;

class BlogService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url = 'http://blog.amsterdamphp.nl/';

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor
     */
    public function __construct(Cache $cache)
    {
        $this->client = new Client($this->url);
        $this->cache = $cache;
    }

    /**
     * Returns the latest blog posts
     *
     * @return mixed
     */
    public function getLatestBlogPosts()
    {
        $cacheKey = 'blog.rss.latest';
        $postsRss = $this->cache->get($cacheKey);

        if ($postsRss === null){
            $postsRss = base64_encode(serialize($this->client->get('/feed')->send()));

            $this->cache->set($cacheKey, $postsRss);
            $this->cache->expireat($cacheKey, strtotime('+5 hours'));
        }

        $postsRss = unserialize(base64_decode($postsRss));

        return (new \SimpleXMLElement($postsRss->getBody()))->channel->item;
    }
}
