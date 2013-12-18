<?php

use DMS\Bundle\TwigExtensionBundle\DMSTwigExtensionBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            #Symfony Standard
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            #Third Party
            new Snc\RedisBundle\SncRedisBundle(),
            new DMS\Bundle\MeetupApiBundle\DMSMeetupApiBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new DoctrineMigrationsBundle(),
            new DMSTwigExtensionBundle(),
            new Ornicar\AkismetBundle\OrnicarAkismetBundle(),
            new Ornicar\GravatarBundle\OrnicarGravatarBundle(),
            new AntiMattr\GoogleBundle\GoogleBundle(),

            #AmsterdamPHP
            new AmsterdamPHP\Bundle\MeetupBundle\AmsterdamPHPMeetupBundle(),
            new AmsterdamPHP\Bundle\SiteBundle\AmsterdamPHPSiteBundle(),
            new AmsterdamPHP\Bundle\SponsorBundle\AmsterdamPHPSponsorBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    protected function getContainerBaseClass()
    {
        if ('test' == $this->environment) {
            return '\PSS\SymfonyMockerContainer\DependencyInjection\MockerContainer';
        }

        return parent::getContainerBaseClass();
    }

//    public function getCacheDir()
//    {
//        if (in_array($this->environment, array('dev', 'test'))) {
//            return '/dev/shm/amsphp/cache/' .  $this->environment;
//        }
//
//        return parent::getCacheDir();
//    }
//
    public function getLogDir()
    {
        if (in_array($this->environment, array('dev', 'test'))) {
            return '/dev/shm/amsphp/logs';
        }

        return parent::getLogDir();
    }
}
