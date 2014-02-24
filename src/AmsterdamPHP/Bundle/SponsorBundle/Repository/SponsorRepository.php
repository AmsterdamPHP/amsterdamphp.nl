<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class SponsorRepository extends EntityRepository
{

    public function getCurrentActiveSponsors($package = null)
    {
        $now = new \DateTime('now');

        $qb = $this->createQueryBuilder('sp');

        $qb->join('sp.packages', 'pkg');

        $qb->andWhere('pkg.startDate <= :now');
        $qb->andWhere('pkg.endDate >= :now');
        $qb->setParameter('now', $now);

        if ($package !== null) {
            $qb->andWhere('pkg.type = :package');
            $qb->setParameter('package', $package);
        }

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    public function getMeetingSponsors($year = null)
    {
        $year = $year ?: (new \DateTime('now'))->format('Y');

        $qb = $this->createQueryBuilder('sp');

        $qb->join('sp.meetings', 'mtg');

        $qb->andWhere('YEAR(mtg.meetingDate) <= :year');
        $qb->setParameter('year', $year);

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
