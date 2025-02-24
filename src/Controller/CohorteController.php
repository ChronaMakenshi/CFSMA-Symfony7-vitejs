<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Classes;
use App\Form\CohorteType;
use App\Entity\Filieres;
use App\Entity\Sections;
use App\Entity\Compagnies;

class CohorteController extends AbstractController
{
    #[Route('/addcohorte', name: 'app_cohorte')]
    #[Route('/addcohorte/{id}', name: 'app_cohorte_edit', requirements: ['id' => '\d+'])]
    public function ajoutecohorte(Classes $cohorte = null, Request $request, ManagerRegistry $coh, EntityManagerInterface $manager): Response
    {
        $cohortes = $coh->getRepository(Classes::class)->findAll();
        $filieres = $coh->getRepository(Filieres::class)->findAll();
        $sections = $coh->getRepository(Sections::class)->findAll();
        $compagnies = $coh->getRepository(Compagnies::class)->findAll();

        if (!$cohorte) {
            $cohorte = new Classes();
        }

        $user = $this->getUser();

        $form = $this->createForm(CohorteType::class, $cohorte, [
            'user' => $user,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($cohorte);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');

            return $this->redirectToRoute('app_cohorte');
        }

        return $this->render('cohorte/addcohorte.html.twig', [
            'filieres' => $filieres,
            'sections' => $sections,
            'compagnies' => $compagnies,
            'cohortes' => $cohortes,
            'formCohorte' => $form->createView(),
            'EditMode' => $cohorte->getId() !== null,
        ]);
    }

    #[Route('/addcohorte/delete/{id}', name: 'delete_cohorte')]
    public function deletecohorte(int $id, ManagerRegistry $coh, EntityManagerInterface $manager): Response
    {
        $cohorte = $coh->getRepository(Classes::class)->find($id);
        if (!$cohorte) {
            throw $this->createNotFoundException('La cohorte n\'existe pas');
        }

        $manager->remove($cohorte);
        $manager->flush();

        $this->addFlash('success', 'La cohorte ' . $cohorte->getName() . ' a été supprimée');

        return $this->redirectToRoute('app_cohorte');
    }
}
