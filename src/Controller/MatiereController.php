<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Matieres;
use App\Entity\Users;
use App\Entity\Filieres;
use App\Entity\Sections;
use App\Entity\Classes;
use App\Entity\Matierepublics;
use Doctrine\ORM\EntityManagerInterface;

class MatiereController extends AbstractController
{
    #[Route('/addmatiere', name: 'app_matiere')]
    #[Route('/addmatiere/{id}', name: 'app_matiere_edit', requirements: ['id' => '\d+'])]
    public function ajoutematiere(Matieres $matiere = null, Request $request, ManagerRegistry $mat, EntityManagerInterface $manager): Response
    {
        $matieres = $mat->getRepository(Matieres::class)->findAll();

        if (!$matiere) {
            $matiere = new Matieres();
        }

        $form = $this->createFormBuilder($matiere)
            ->add('name', TextType::class, ['label' => false])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($matiere);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');

            return $this->redirectToRoute('app_matiere');
        }

        return $this->render('matiere/addmatiere.html.twig', [
            'matieres' => $matieres,
            'formMatiere' => $form->createView(),
            'EditMode' => $matiere->getId() !== null,
        ]);
    }

    #[Route('/addmatiere/delete/{id}', name: 'app_matiere_delete')]
    public function deletematiere(int $id, ManagerRegistry $mat, EntityManagerInterface $manager): Response
    {
        $matiere = $mat->getRepository(Matieres::class)->find($id);
        if (!$matiere) {
            throw $this->createNotFoundException('La matière n\'existe pas');
        }

        $manager->remove($matiere);
        $manager->flush();

        $this->addFlash('success', 'La matière ' . $matiere->getName() . ' a été supprimée');

        return $this->redirectToRoute('app_matiere');
    }

    #[Route('/addmatierepublic', name: 'app_matiere_public')]
    #[Route('/addmatierepublic/{id}', name: 'add_matierepublic_edit', requirements: ['id' => '\d+'])]
    public function ajoutematierepublic(Matierepublics $matierepublic = null, Request $request, ManagerRegistry $matp, EntityManagerInterface $manager): Response
    {
        $matierepublics = $matp->getRepository(Matierepublics::class)->findAll();

        if (!$matierepublic) {
            $matierepublic = new Matierepublics();
        }

        $form = $this->createFormBuilder($matierepublic)
            ->add('name', TextType::class, ['label' => false])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($matierepublic);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');

            return $this->redirectToRoute('app_matiere_public');
        }

        return $this->render('matiere/addmatierepublic.html.twig', [
            'matierepublics' => $matierepublics,
            'formMatierepublic' => $form->createView(),
            'EditMode' => $matierepublic->getId() !== null,
        ]);
    }

    #[Route('/addmatierepublic/delete/{id}', name: 'app_matierepublic_delete')]
    public function deletematierepublic(int $id, ManagerRegistry $matp, EntityManagerInterface $manager): Response
    {
        $matierepublic = $matp->getRepository(Matierepublics::class)->find($id);
        if (!$matierepublic) {
            throw $this->createNotFoundException('La matière n\'existe pas');
        }

        $manager->remove($matierepublic);
        $manager->flush();

        $this->addFlash('success', 'La matière ' . $matierepublic->getName() . ' a été supprimée');

        return $this->redirectToRoute('add_matierepublic');
    }

    #[Route('/matiere', name: 'matiere')]
    public function pagematiere(ManagerRegistry $mat): Response
    {
        $users = $mat->getRepository(Users::class)->findAll();
        $filieres = $mat->getRepository(Filieres::class)->findAll();
        $sections = $mat->getRepository(Sections::class)->findAll();
        $matieres = $mat->getRepository(Matieres::class)->findAll();
        $cohortes = $mat->getRepository(Classes::class)->findAll();

        return $this->render('matiere/pagedesmatiere.html.twig', [
            'controller_name' => 'CfsmaController',
            'users' => $users,
            'matieres' => $matieres,
            'sections' => $sections,
            'filieres' => $filieres,
            'cohortes' => $cohortes,
        ]);
    }

    #[Route('/public', name: 'public')]
    public function pagematierep(ManagerRegistry $matp): Response
    {
        $matierepublics = $matp->getRepository(Matierepublics::class)->findAll();

        return $this->render('matiere/pagedesmatierepublic.html.twig', [
            'matierepublics' => $matierepublics,
        ]);
    }
}
