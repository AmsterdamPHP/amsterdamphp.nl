<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Form;

use AmsterdamPHP\Bundle\SponsorBundle\Form\Choice\MeetingDateChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
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
            ->add('meeting_date', 'choice', [
                'choices' => MeetingDateChoiceList::getChoices(),
                'data' => MeetingDateChoiceList::getClosestDateTime()

            ])
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

        $builder->get('meeting_date')->addModelTransformer(new DateTimeToTimestampTransformer());
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
