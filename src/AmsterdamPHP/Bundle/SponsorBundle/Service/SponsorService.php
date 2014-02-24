<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Service;

use AmsterdamPHP\Bundle\SponsorBundle\Entity\Sponsor;
use AmsterdamPHP\Bundle\SponsorBundle\Entity\SponsorList;
use AmsterdamPHP\Bundle\SponsorBundle\Repository\SponsorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class SponsorService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SponsorRepository
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $entityManager->getRepository('AmsterdamPHPSponsorBundle:Sponsor');
    }

    /**
     * @return ArrayCollection
     */
    public function getCurrentlyActiveSponsors()
    {
        return $this->repository->getCurrentActiveSponsors();
    }

    /**
     * @return SponsorList
     */
    public function getCurrentlyActiveSponsorsByPackage()
    {
        $list = new SponsorList();
        $list->addSponsorList($this->getCurrentlyActiveSponsors());

        return $list;
    }

    /**
     * @param int $max
     * @return ArrayCollection
     */
    public function getMeetingSponsors($max = null)
    {
        return $this->repository->getMeetingSponsors($max);
    }
}
