<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Classes;
use App\Repository\ClassesRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationStagiaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['user']) || !$options['user'] instanceof Users) {
            throw new \InvalidArgumentException("L'utilisateur doit être défini pour le formulaire.");
        }

        $user = $options['user'];

        $builder
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmer :'],
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe devrait être d\'au moins {{ limit }} caractères',
                        'max' => 12,
                        'maxMessage' => 'Votre mot de passe ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => false,
                    'choices' => [
                        'Stagiaire' => 'ROLE_USER',
                    ],
                ],
            ])
            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => fn(Classes $classe) => $classe->getFiliere()->getName() . '-' . $classe->getName(),
                'choice_value' => 'id',
                'label' => false,
                'query_builder' => function (EntityRepository $classesRepository) use ($user) {
                    $qb = $classesRepository->createQueryBuilder('c')
                        ->join('c.filiere', 'f')
                        ->join('f.section', 's')
                        ->orderBy('s.name', 'ASC');

                    // Si l'utilisateur n'est ni admin ni super admin, on filtre les sections et filières
                    if (!in_array('ROLE_ADMIN', $user->getRoles(), true) && !in_array('ROLE_SUPERADMIN', $user->getRoles(), true)) {
                        if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
                            // Filtrer par section pour les chefs de section
                            $qb->where('s.id = :sectionId')
                                ->setParameter('sectionId', $user->getSection()->getId());
                        } elseif (in_array('ROLE_FORMATEUR', $user->getRoles(), true)) {
                            // Filtrer par formateur pour les formateurs
                            $qb->where('f.id = :filiereId')
                                ->setParameter('filiereId', $user->getFiliere()->getId());
                        }
                    }

                    return $qb;
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'user' => null,
        ]);

        $resolver->setDefined(['user']);
    }
}