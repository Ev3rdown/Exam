<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a name',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Your name should be at least {{ limit }} characters',
                    'max' => 255,
                    'minMessage' => 'Your name should be less than {{ limit }} characters',
                ]),
            ]
        ])
        ->add('color',TextType::class,[
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a color',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Your color should be at least {{ limit }} characters',
                    'max' => 255,
                    'minMessage' => 'Your color should be less than {{ limit }} characters',
                ]),
            ]
        ])
        ->add('city',TextType::class,[
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a city',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Your city should be at least {{ limit }} characters',
                    'max' => 255,
                    'minMessage' => 'Your city should be less than {{ limit }} characters',
                ]),
            ]
        ])
            ->add('competition', EntityType::class, [
                // looks for choices from this entity
                'class' => Competition::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => false,
                // 'expanded' => true,
                'required' => false
            ])
            ->add('players', EntityType::class, [
                // looks for choices from this entity
                'class' => Player::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'firstname',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Save modifications'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
