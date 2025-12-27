<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire']),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre prénom'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire']),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '+216 XX XXX XXX'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le téléphone est obligatoire']),
                    new Assert\Regex([
                        'pattern' => '/^[\d\s\+\-\(\)]+$/',
                        'message' => 'Numéro de téléphone invalide'
                    ])
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'votre@email.com'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                    new Assert\Email(['message' => 'Email invalide'])
                ]
            ])
            ->add('dateReservation', DateType::class, [
                'label' => 'Date de réservation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d')
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date est obligatoire']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être aujourd\'hui ou ultérieure'
                    ])
                ]
            ])
            ->add('heureReservation', TimeType::class, [
                'label' => 'Heure de réservation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'heure est obligatoire'])
                ]
            ])
            ->add('nombrePersonnes', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 20
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nombre de personnes est obligatoire']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 20,
                        'notInRangeMessage' => 'Le nombre de personnes doit être entre {{ min }} et {{ max }}'
                    ])
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire (optionnel)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Allergies, demandes spéciales'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
