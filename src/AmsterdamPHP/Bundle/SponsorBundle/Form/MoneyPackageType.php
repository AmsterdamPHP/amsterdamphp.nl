<?php

namespace AmsterdamPHP\Bundle\SponsorBundle\Form;

use AmsterdamPHP\Bundle\SponsorBundle\Entity\MoneyPackage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MoneyPackageType
 *
 * @package AmsterdamPHP\Bundle\SponsorBundle\Form
 */
class MoneyPackageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('start_date', 'date', ['data' => new \DateTime('+1 day')])
            ->add('period', 'text', ['mapped' => false])
        ;

        $builder
            ->add('submit', 'submit', array('label' => 'Add'));

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                /** @var MoneyPackage $package */

                $package = $event->getData();
                $period = $event->getForm()->get('period')->getData();

                if ($package->getStartDate() === null) {
                    return;
                }

                $endDate = clone $package->getStartDate();
                $endDate->add(new \DateInterval($period));
                $package->setEndDate($endDate);
            },
            255
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AmsterdamPHP\Bundle\SponsorBundle\Entity\MoneyPackage'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'amsphp_sponsor_package_money';
    }
}
