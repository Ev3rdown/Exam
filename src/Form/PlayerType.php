<?php

namespace App\Form;

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

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a firstname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your firstname should be at least {{ limit }} characters',
                        'max' => 255,
                        'minMessage' => 'Your firstname should be less than {{ limit }} characters',
                    ]),
                ]
            ])
            ->add('lastname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a lastname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your lastname should be at least {{ limit }} characters',
                        'max' => 255,
                        'minMessage' => 'Your lastname should be less than {{ limit }} characters',
                    ]),
                ]
            ])
            ->add('role',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a role',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your role should be at least {{ limit }} characters',
                        'max' => 255,
                        'minMessage' => 'Your role should be less than {{ limit }} characters',
                    ]),
                ]
            ])
            /* ->add('teams', EntityType::class, [
                // looks for choices from this entity
                'class' => Team::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ]) */
            ->add('save', SubmitType::class,[
                'label' => 'Save modifications'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
