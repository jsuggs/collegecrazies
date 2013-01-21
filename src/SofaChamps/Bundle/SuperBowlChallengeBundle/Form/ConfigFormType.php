<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'hidden')
            ->add('startTime', 'datetime')
            ->add('closeTime', 'datetime')
            ->add('finalScorePoints', 'integer')
            ->add('halftimeScorePoints', 'integer')
            ->add('firstTeamToScoreInAQuarterPoints', 'integer')
            ->add('neitherTeamToScoreInAQuarterPoints', 'integer')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config',
        ));
    }

    public function getName()
    {
        return 'config';
    }
}
