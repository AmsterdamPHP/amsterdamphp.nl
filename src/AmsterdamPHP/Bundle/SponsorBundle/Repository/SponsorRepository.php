<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class SponsorRepository extends EntityRepository
{

    /**
     * @param null $package
     * @return ArrayCollection
     */
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

    /**
     * @param null $year
     * @return ArrayCollection
     */
    public function getMeetingSponsors($max = null, $year = null)
    {
        $year = $year ?: (new \DateTime('now'))->format('Y');

        $qb = $this->createQueryBuilder('sp');

        $qb->join('sp.meetings', 'mtg');

        $qb->andWhere('SUBSTRING(mtg.meetingDate, 0, 4) <= :year');
        $qb->setParameter('year', $year);

        if ($max !== null) {
            $qb->setMaxResults($max);
        }

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
