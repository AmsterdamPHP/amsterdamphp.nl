<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                    'attr' => [
                        'placeholder' => 'What\'s your name?',
                        'pattern'     => '.{2,}',
                        'class'         => 'input-xxlarge',
                    ]
                ])
            ->add('email', 'email', [
                    'attr' => [
                        'placeholder' => 'So we can get back in touch with you.',
                        'class'         => 'input-xxlarge',
                    ]
                ])
            ->add('subject', 'text', [
                    'attr' => [
                        'placeholder' => 'What\'s your purpose? Sponsor, Speaker, etc...',
                        'pattern'     => '.{3,}',
                        'class'         => 'input-xxlarge',
                    ]
                ])
            ->add('message', 'textarea', [
                    'attr' => [
                        'cols' => 90,
                        'rows' => 10,
                        'placeholder' => 'Give us some details..',
                        'class'         => 'input-xxlarge',
                    ]
                ])

            ->add('send', 'submit');
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection([
            'name' => [
                new NotBlank(['message' => 'Name should not be blank.']),
                new Length(['min' => 2])
            ],
            'email' => [
                new NotBlank(['message' => 'Email should not be blank.']),
                new Email(['message' => 'Invalid email address.'])
            ],
            'subject' => [
                new NotBlank(['message' => 'Subject should not be blank.']),
                new Length(['min' => 3])
            ],
            'message' => [
                new NotBlank(['message' => 'Message should not be blank.']),
                new Length(['min' => 5])
            ]
        ]);

        $resolver->setDefaults([
                'constraints' => $collectionConstraint
            ]);
    }

    public function getName()
    {
        return 'amsterdamphp_site_contact_type';
    }
}
