<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TravelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDateTime', null, array('widget' => 'single_text'))
            ->add('endDateTime', null, array('widget' => 'single_text'))
            ->add('name', 'text')
            ->add('save', 'submit', array('label' => 'Create Travel'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Travel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'travel';
    }
}
