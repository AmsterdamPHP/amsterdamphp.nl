<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MoneyPackage
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Entity
 *
 * @ORM\Table(name="package_money")
 * @ORM\Entity(repositoryClass="AmsterdamPHP\Bundle\SponsorBundle\Repository\MoneyPackageRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "community" = "CommunityPackage", "bronze" = "BronzePackage", "silver" = "SilverPackage", "gold" = "GoldPackage",
 *      "generic" = "GenericPackage"
 * })
 */
abstract class MoneyPackage extends Package
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $value;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    protected $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    protected $endDate;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $details;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->startDate = new DateTime();
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

}
