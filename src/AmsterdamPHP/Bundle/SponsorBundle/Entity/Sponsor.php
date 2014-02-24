<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="AmsterdamPHP\Bundle\SponsorBundle\Repository\SponsorRepository")
 * @ORM\Table(name="sponsor")
 * @ORM\HasLifecycleCallbacks
 *
 * @todo Make the upload stuff a trait + listener and move it out.
 */
class Sponsor
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_extension", type="string", length=255)
     */
    private $logoExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_md5", type="string", length=32, nullable=true)
     */
    private $logoMd5;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $uploadedFile;

    /**
     * @var string
     */
    private $previousFile;

    /**
     * @var Package[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="MoneyPackage", mappedBy="sponsor")
     */
    private $packages;

    /**
     * @var MeetingPackage[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="MeetingPackage", mappedBy="sponsor")
     */
    private $meetings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->packages = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $imageUrl
     */
    public function setLogoExtension($imageUrl)
    {
        $this->logoExtension = $imageUrl;
    }

    /**
     * @return string
     */
    public function getLogoExtension()
    {
        return $this->logoExtension;
    }


    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setUploadedFile(UploadedFile $file = null)
    {
        $this->uploadedFile = $file;

        if ($file !== null) {
            $this->logoMd5 = md5($file->getFilename() . $file->getMTime());
        }
        // check if we have an old image path
        if (is_file($this->getLogoPath())) {
            // store the old name to delete after the update
            $this->previousFile = $this->getLogoPath();
        } else {
            $this->avatarPath = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getUploadedFile()) {
            $this->logoExtension = $this->getUploadedFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getUploadedFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->previousFile)) {
            // delete the old image
            unlink($this->previousFile);
            // clear the temp image path
            $this->previousFile = null;
        }

        $this->getUploadedFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getUploadedFile()->guessExtension()
        );

        $this->setUploadedFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->previousFile = $this->getLogoPath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->previousFile)) {
            unlink($this->previousFile);
        }
    }

    /**
     * Gets either the url to the logo, or the path to it
     *
     * @param bool $url
     * @return null|string
     */
    public function getLogo($url = true)
    {
        if ($url) {
            return $this->getLogoUrl();
        }

        return $this->getLogoPath();
    }

    /**
     * @return null|string
     */
    public function getLogoPath()
    {
        return null === $this->logoExtension
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->logoExtension;
    }

    /**
     * @return null|string
     */
    public function getLogoUrl()
    {
        return null === $this->logoExtension
            ? null
            : $this->getUploadDir().'/'.$this->id.'.'.$this->logoExtension;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return WEB_PATH.'/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return '/uploads';
    }

    /**
     * @param \AmsterdamPHP\Bundle\SponsorBundle\Entity\Package[]|\Doctrine\Common\Collections\ArrayCollection $packages
     */
    public function setPackages($packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return \AmsterdamPHP\Bundle\SponsorBundle\Entity\Package[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param MoneyPackage $package
     */
    public function addPackage(MoneyPackage $package)
    {
        if ($this->packages->contains($package)) {
            return;
        }

        $this->getPackages()->add($package);
    }

    /**
     * @param \AmsterdamPHP\Bundle\SponsorBundle\Entity\MeetingPackage[]|\Doctrine\Common\Collections\ArrayCollection $meetings
     */
    public function setMeetings($meetings)
    {
        $this->meetings = $meetings;
    }

    /**
     * @return \AmsterdamPHP\Bundle\SponsorBundle\Entity\MeetingPackage[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * @param MeetingPackage $meeting
     */
    public function addMeeting(MeetingPackage $meeting)
    {
        if ($this->getMeetings()->contains($meeting)) {
            return;
        }

        $this->getMeetings()->add($meeting);
    }
    /**
     * Get the currently valid packages
     *
     * @param \DateTime $time
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getCurrentMoneyPackages(\DateTime $time = null)
    {
        if ($time === null) {
            $time = new \DateTime('now');
        }

        $packages = $this->getPackages();
        return $packages->filter(function ($package) use ($time) {

            /** @var MoneyPackage $package */
            $start = $package->getStartDate()->format('U');
            $end   = $package->getEndDate()->format('U');
            $moment = $time->format('U');

            return ($moment >= $start && $moment <= $end);
        });
    }
}
