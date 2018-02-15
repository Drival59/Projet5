<?php

namespace HV\UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UsersPwdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->remove('login')
          ->remove('email')
          ->remove('confirmEmail')
          ->remove('avatar');
    }

    public function getParent()
    {
      return UsersType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hv_usersbundle_usersPwd';
    }

}
