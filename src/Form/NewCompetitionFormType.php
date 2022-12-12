<?php

namespace App\Form;

use App\Entity\TeamSeason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewCompetitionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'mapped' => false,
            ])
            ->add('ranking', TextType::class, [
                'label' => 'Résultat',
                'required' => true,
                'mapped' => false,
            ])
            ->add('matches', IntegerType::class, [
                'label' => 'M',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('victories', IntegerType::class, [
                'label' => 'V',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('draws', IntegerType::class, [
                'label' => 'N',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('defeats', IntegerType::class, [
                'label' => 'D',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('g', IntegerType::class, [
                'label' => 'BP',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 150,
                ],
            ])
            ->add('ga', IntegerType::class, [
                'label' => 'BC',
                'required' => true,
                'mapped' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 150,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamSeason::class,
        ]);
    }
}