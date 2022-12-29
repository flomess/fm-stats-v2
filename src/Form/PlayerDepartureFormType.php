<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerDepartureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure_type', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Type',
                'choices' => [
                    "Transfert" => "transfert",
                    "Fin de Contrat" => "f.c.",
                    "Retour de Prêt" => "r.p.",
                    "Prêt avec Option d'Achat" => "p.o.a.",
                    "Retraite" => "retraite",
                    "Libre" => "libre",
                ],
            ])
            ->add('departure_date', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Année',
                'placeholder' => '-- Année --',
                'choices' => $options["years"]
            ])
            ->add('departure_winter', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'value' => false,
                'label' => 'Hiver',
            ])
            ->add('departure_team', TextType::class, [
                'mapped' => false,
                'label' => 'Club',
                'empty_data' => null
            ])
            ->add('departure_team_country', ChoiceType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Pays',
                'empty_data' => null,
                'placeholder' => '-- Choisissez un pays --',
                'choices' => $options['clubCountries'],
            ])
            ->add('departure_team_division', ChoiceType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Div.',
                'empty_data' => null,
                'data' => '1',
                'placeholder' => '--',
                'choices' => [
                    "D1" => "1",
                    "D2" => "2",
                    "D3" => "3",
                    "D4" => "4",
                    "D5" => "5",
                    "D6" => "6",
                    "D7" => "7",
                    "D8" => "8"
                ],
            ])
            ->add('departure_amount', TextType::class, [
                'mapped' => false,
                'label' => 'Montant',
                'empty_data' => null
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
            'clubCountries' => [],
            'years' => []
        ]);
    }
}