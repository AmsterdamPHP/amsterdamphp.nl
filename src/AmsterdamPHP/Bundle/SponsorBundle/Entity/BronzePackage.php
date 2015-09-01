<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BronzePackage
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Entity
 *
 * @ORM\Entity()
 */
class BronzePackage extends MoneyPackage
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = 500;
    }
}
