<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeetingPackageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meeting_date', 'date')
            ->add(
                'meeting_type',
                'choice',
                [
                    'choices' => [
                        'monthly' => 'Monthly Meeting',
                        'drinks' => 'Geeks and Drinks',
                        'hackathon' => 'Hackathons',
                        'test-fest' => 'TestFest',
                    ]
                ]
            )
        ;

        $builder
            ->add('submit', 'submit', array('label' => 'Add'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AmsterdamPHP\Bundle\SponsorBundle\Entity\MeetingPackage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'amsphp_sponsor_package_meeting';
    }
}
