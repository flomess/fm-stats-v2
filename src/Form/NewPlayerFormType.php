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

class NewPlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('country', ChoiceType::class, [
                'required' => true,
                'label' => 'Nationalité',
                'placeholder' => '-- Choisissez un pays --',
                'choices' => $options['countries'],
            ])
            ->add('position', ChoiceType::class, [
                'required' => true,
                'label' => 'Poste',
                'placeholder' => '-- Poste --',
                'choices' => [
                    "Gardien" => "Gardien",
                    "Défenseur" => "Défenseur",
                    "Latéral" => "Latéral",
                    "Milieu" => "Milieu",
                    "Ailier" => "Ailier",
                    "Attaquant" => "Attaquant"
                ],
            ])
            ->add('arrival_type', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Type',
                'choices' => [
                    "Transfert" => "transfert",
                    "Prêt" => "p.",
                    "Fin de Contrat" => "f.c.",
                    "Formé au Club" => "forme",
                    "Prêt avec Option d'Achat" => "p.o.a.",
                    "Origine" => "origine"
                ],
            ])
            ->add('arrival_date', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Année',
                'placeholder' => '-- Année --',
                'choices' => [
                    "2020" => "2020",
                    "2021" => "2021",
                    "2022" => "2022",
                    "2023" => "2023",
                    "2024" => "2024",
                    "2025" => "2025",
                    "2026" => "2026",
                    "2027" => "2027",
                    "2028" => "2028",
                    "2029" => "2029",
                    "2030" => "2030",
                    "2031" => "2031",
                    "2032" => "2032",
                ],
            ])
            ->add('arrival_winter', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'value' => false,
                'label' => 'Hiver',
            ])
            ->add('arrival_team', TextType::class, [
                'mapped' => false,
                'label' => 'Club',
            ])
            ->add('arrival_team_country', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Pays',
                'placeholder' => '-- Choisissez un pays --',
                'choices' => $options['clubCountries'],
            ])
            ->add('arrival_team_division', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Div.',
                'placeholder' => '--',
                'data' => '1',
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
            ->add('arrival_amount', TextType::class, [
                'mapped' => false,
                'label' => 'Montant',
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
            'countries' => [],
        ]);
    }
}