<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class SponsorList
{

    /**
     * @var ArrayCollection
     */
    protected $gold;

    /**
     * @var ArrayCollection
     */
    protected $silver;

    /**
     * @var ArrayCollection
     */
    protected $bronze;

    /**
     * @var ArrayCollection
     */
    protected $community;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gold      = new ArrayCollection();
        $this->silver    = new ArrayCollection();
        $this->bronze    = new ArrayCollection();
        $this->community = new ArrayCollection();
    }

    /**
     * @param Sponsor[] $sponsors
     */
    public function addSponsorList($sponsors)
    {
        foreach ($sponsors as $sponsor) {
            $this->addSponsor($sponsor);
        }
    }

    /**
     * @param Sponsor $sponsor
     */
    public function addSponsor(Sponsor $sponsor)
    {
        foreach ($sponsor->getCurrentMoneyPackages() as $package) {
            $this->storeByPackageType($package);
        }
    }

    /**
     * @param MoneyPackage $package
     * @return bool
     */
    protected function storeByPackageType(MoneyPackage $package)
    {
        if ($package instanceof GoldPackage) {
            return $this->gold->add($package->getSponsor());
        }
        if ($package instanceof SilverPackage) {
            return $this->silver->add($package->getSponsor());
        }
        if ($package instanceof BronzePackage) {
            return $this->bronze->add($package->getSponsor());
        }
        if ($package instanceof CommunityPackage) {
            return $this->community->add($package->getSponsor());
        }

        return true;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBronze()
    {
        return $this->bronze;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSilver()
    {
        return $this->silver;
    }

}
