<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CarImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('dateTime', 'date', ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('isAvatar')
            ->add('description')
            ->add('file', FileType::class, array('label' => 'Image (image file)'))
            ->add('save', 'submit', array('label' => 'Save'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarBundle\Entity\CarImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carbundle_carimage';
    }
}