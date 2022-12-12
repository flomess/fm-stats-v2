<?php

namespace App\Form;

use App\Entity\TeamSeason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTeamSeasonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'label' => 'Année',
                'required' => true,
                'choices' => [
                    '2020-2021' => '2020-2021',
                    '2021-2022' => '2021-2022',
                    '2022-2023' => '2022-2023',
                    '2023-2024' => '2023-2024',
                    '2024-2025' => '2024-2025',
                    '2025-2026' => '2025-2026',
                    '2026-2027' => '2026-2027',
                    '2027-2028' => '2027-2028',
                    '2028-2029' => '2028-2029',
                    '2029-2030' => '2029-2030',
                    '2030-2031' => '2030-2031',
                    '2031-2032' => '2031-2032',
                    '2032-2033' => '2032-2033',
                ]
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