<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommunityPackageType extends MoneyPackageType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AmsterdamPHP\Bundle\SponsorBundle\Entity\CommunityPackage'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'amsphp_sponsor_package_community';
    }
}
