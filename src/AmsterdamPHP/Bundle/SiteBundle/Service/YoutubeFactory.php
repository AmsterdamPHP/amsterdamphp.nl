<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Service;

use Madcoda\Youtube;

class YoutubeFactory
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return Youtube
     */
    public function getClient()
    {
        return new Youtube(array(
            'key' => $this->key,
        ));
    }
}