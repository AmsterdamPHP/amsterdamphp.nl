<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenericPackageType extends MoneyPackageType
{

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AmsterdamPHP\Bundle\SponsorBundle\Entity\GenericPackage'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'amsphp_sponsor_package_generic';
    }
}
