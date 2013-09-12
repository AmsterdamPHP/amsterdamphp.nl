<?php
namespace AmsterdamPHP\Behat;

use Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Sanpi\Behatch\Context\BehatchContext;

class FeatureContext extends MinkContext
{
    public function __construct(array $parameters)
    {
        $this->useContext('behatch', new BehatchContext($parameters));
        $this->useContext('meetup', new MeetupContext($parameters));
    }
}
