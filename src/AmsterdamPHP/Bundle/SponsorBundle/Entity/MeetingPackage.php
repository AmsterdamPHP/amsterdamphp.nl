<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MeetingPackage
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Entity
 *
 * @ORM\Table(name="package_meeting")
 * @ORM\Entity(repositoryClass="AmsterdamPHP\Bundle\SponsorBundle\Repository\MeetingPackageRepository")
 */
class MeetingPackage extends Package
{
    /**
     * @var DateTime
     *
     * @ORM\Column(name="meeting_date", type="date")
     */
    protected $meetingDate;

    /**
     * @var string
     *
     * @ORM\Column(name="meeting_type", type="string", length=30)
     */
    protected $meetingType;

    /**
     * @param \DateTime $meetingDate
     */
    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;
    }

    /**
     * @return \DateTime
     */
    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    /**
     * @param string $meetingType
     */
    public function setMeetingType($meetingType)
    {
        $this->meetingType = $meetingType;
    }

    /**
     * @return string
     */
    public function getMeetingType()
    {
        return $this->meetingType;
    }

}
