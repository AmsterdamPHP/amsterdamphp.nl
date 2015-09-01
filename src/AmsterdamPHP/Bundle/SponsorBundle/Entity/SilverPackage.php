<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SilverPackage
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Entity
 *
 * @ORM\Entity()
 */
class SilverPackage extends MoneyPackage
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = 1000;
    }
}
