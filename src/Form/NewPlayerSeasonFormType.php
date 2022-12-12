<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\PlayerSeason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPlayerSeasonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('player', ChoiceType::class, [
                'label' => 'Joueur',
                'required' => true,
                'choices' => $options['players'],
                'choice_label' => function (Player $player) {
                    return $player->getFirstname() . ' ' . $player->getLastname();
                },
                'placeholder' => '-- Choisissez un joueur --'
            ])
            ->add('is_loaned', CheckboxType::class, [
                'label' => 'Prêté',
                'required' => false,
                'data' => false
            ])
            ->add('half_season', CheckboxType::class, [
                'label' => 'Demi-Saison',
                'required' => false,
                'mapped' => false,
                'data' => false
            ])
            ->add('loan_team', TextType::class, [
                'label' => 'Club',
                'mapped' => false,
                'required' => false
            ])
            ->add('loan_team_division', ChoiceType::class, [
                'label' => 'Div',
                'mapped' => false,
                'required' => false,
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
            ->add('loan_team_country', ChoiceType::class, [
                'label' => 'Pays',
                'mapped' => false,
                'required' => false,
                'placeholder' => '-- Choisissez un pays --',
                'choices' => $options['clubCountries']
            ])
            ->add('matches', IntegerType::class, [
                'label' => 'Matchs',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('first_team', IntegerType::class, [
                'label' => 'Titu.',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('goals', IntegerType::class, [
                'label' => 'Buts',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('assists', IntegerType::class, [
                'label' => 'Passes',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('yellow_cards', IntegerType::class, [
                'label' => 'CJ',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('red_cards', IntegerType::class, [
                'label' => 'CR',
                "mapped" => false,
                'required' => false,
                'data' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('rate', NumberType::class, [
                'label' => 'Note',
                "mapped" => false,
                'required' => false,
                'data' => '0.00',
                'scale' => 2,
                'attr' => [
                    'min' => 0,
                    'step' => 0.01,
                    'max' => 10,
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerSeason::class,
            'players' => [],
            'clubCountries' => [],
        ]);
    }
}