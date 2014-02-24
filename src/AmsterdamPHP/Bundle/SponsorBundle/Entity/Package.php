<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 * @ORM\MappedSuperclass
 */
abstract class Package
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Sponsor
     * @ORM\ManyToOne(targetEntity="Sponsor", inversedBy="packages")
     */
    protected $sponsor;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**    /**
     * @return string
     */
    public function getType()
    {
        $reflection = new \ReflectionClass($this);

        return str_replace("package", "", strtolower($reflection->getShortName()));
    }

    /**
     * @param \AmsterdamPHP\Bundle\SponsorBundle\Entity\Sponsor $sponsor
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    }

    /**
     * @return \AmsterdamPHP\Bundle\SponsorBundle\Entity\Sponsor
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

}
