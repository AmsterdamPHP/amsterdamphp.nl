<?php

namespace AmsterdamPHP\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LoginType
 *
 * @package AmsterdamPHP\Bundle\UserBundle\Form
 */
class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('password', 'password');
        $builder->add(
            '_remember_me',
            'checkbox',
            ['required' => false]
        );
        $builder->add('_target_path', 'hidden');
        $builder->add('actions', 'form_actions', [
            'buttons' =>[
                'submit' => ['type' => 'submit']
                ]
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'amsterdamphp_login';
    }

}
