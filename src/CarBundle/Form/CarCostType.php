<?php

namespace CarBundle\Form;

use CarBundle\Entity\TranslationContainer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarCostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (TranslationContainer::Instance()->carCostTypes as $typeId => $typeDetails) {
            $choices[$typeDetails['name']] = $typeId;
        }
        $currency = new \CarBundle\Entity\Currency();
        $builder
            ->add('type', ChoiceType::class, [
                'choices'  => $choices,
                'choices_as_values' => true,
            ])
            ->add('dateTime', 'date', ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('mileage')
            ->add('amount')
            ->add('currency', ChoiceType::class, [
                'choices'  => $currency->getCurrencies(),
                'choices_as_values' => false,
            ])
            ->add('description')
            ->add('save', 'submit', array('label' => 'Save'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarBundle\Entity\CarCost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carbundle_carcost';
    }
}
