<?php

namespace App\Form;

use App\Entity\Courpublics;
use App\Entity\Matierepublics;
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

class CourpublicsType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => false])
            ->add('coursFilesp', FileType::class, [
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
            ->add('matierepublic', EntityType::class, [
                'class' => Matierepublics::class,
                'choice_label' => 'name',
                'label' => false,
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'widget' => 'choice',
                'format' => 'ddMMyyyy',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Courpublics::class,
            'is_edit' => false,
        ]);
    }
}