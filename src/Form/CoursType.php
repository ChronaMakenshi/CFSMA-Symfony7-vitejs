<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Classes;
use App\Entity\Matieres;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class CoursType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('name', TextType::class, ['label' => false])
            ->add('coursFiles', FileType::class, [
                'label' => false,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'application/pdf',
                                'video/x-msvideo',
                                'video/mpeg',
                                'application/zip',
                                'application/x-rar-compressed',
                            ],
                            'mimeTypesMessage' => 'Les formats PDF, AVI, MPEG, ZIP, RAR, moins de 10M, s\'il vous plaît',
                        ]),
                    ]),
                ],
                'multiple' => true,
                'mapped' => false,
                'required' => !$options['is_edit'],
            ])
            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => function (Classes $coh) {
                    return $coh->getFiliere()->getName() . '-' . $coh->getName();
                },
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
                'label' => false,
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matieres::class,
                'choice_label' => 'name',
                'label' => false,
            ])
            ->add('date', DateType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => false,
                'html5' => false,
                'widget' => 'choice',
                'format' => 'ddMMyyyy',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
            'user' => $this->security->getUser(), // Définir l'option user avec l'utilisateur courant de la sécurité
            'is_edit' => false, // Par défaut, on considère que ce n'est pas une édition
        ]);

        $resolver->setDefined(['is_edit']); // Définit l'option 'is_edit'
    }
}