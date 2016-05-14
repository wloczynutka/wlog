<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make')
            ->add('model')
            ->add('user')
            ->add('manufactureDate')
            ->add('color')
            ->add('fuel')
            ->add('save', 'submit', array('label' => 'Save'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarBundle\Entity\Car'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carbundle_car';
    }
}
