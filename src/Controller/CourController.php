<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use App\Entity\Classes;
use App\Entity\Filieres;
use App\Entity\Matieres;
use App\Entity\CoursFiles;
use App\Entity\Courpublics;
use App\Entity\Matierepublics;
use App\Entity\CoursFilesp;
use App\Entity\Sections;
use App\Form\CoursType;
use App\Form\CourpublicsType;

class CourController extends AbstractController
{
    #[Route('/addcours', name: 'app_cour')]
    #[Route('/addcours/{id}', name: 'app_cour_edit', requirements: ['id' => '\d+'])]
    public function ajoutecours(Cours $cour = null, Request $request, ManagerRegistry $doctrine, EntityManagerInterface $manager): Response
    {
        $cours = $doctrine->getRepository(Cours::class)->findAll();
        $cohortes = $doctrine->getRepository(Classes::class)->findAll();
        $filieres = $doctrine->getRepository(Filieres::class)->findAll();
        $matieres = $doctrine->getRepository(Matieres::class)->findAll();

        $isEdit = $cour !== null;

        if (!$isEdit) {
            $cour = new Cours();
            $cour->setDate(new \DateTime('now'));
        }

        $form = $this->createForm(CoursType::class, $cour, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('coursFiles')->getData();

            if (!empty($files)) { // Vérifiez si des fichiers ont été téléchargés
                foreach ($files as $file) {
                    $fichier = $file->getClientOriginalName();
                    $file->move(
                        $this->getParameter('cours_directory2'),
                        $fichier
                    );

                    $cfile = new CoursFiles();
                    $cfile->setName($fichier);
                    $cour->addCoursFile($cfile);
                }
            }

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');
            $manager->persist($cour);
            $manager->flush();

            return $this->redirectToRoute('app_cour');
        }

        return $this->render('cour/addcours.html.twig', [
            'cours' => $cours,
            'filieres' => $filieres,
            'matieres' => $matieres,
            'cohortes' => $cohortes,
            'formCours' => $form->createView(),
            'EditMode' => $isEdit,
        ]);
    }

    #[Route('/addcours/delete/{id}', name: 'app_cour_delete')]
    public function deletecours(int $id, ManagerRegistry $doctrine, EntityManagerInterface $manager): Response
    {
        $cour = $doctrine->getRepository(Cours::class)->find($id);
        if (!$cour) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }

        $manager->remove($cour);
        $manager->flush();

        $this->addFlash('success', 'Le cours ' . $cour->getName() . ' a été supprimé');

        return $this->redirectToRoute('app_cour');
    }

    #[Route('/addcourspublic', name: 'app_cour_public')]
    #[Route('/addcourspublic/{id}', name: 'app_cour_public_edit', requirements: ['id' => '\d+'])]
    public function ajoutecourspublic(Courpublics $courp = null, Request $request, ManagerRegistry $doctrine, EntityManagerInterface $manager): Response
    {
        $courpublics = $doctrine->getRepository(Courpublics::class)->findAll();
        $matierepublics = $doctrine->getRepository(Matierepublics::class)->findAll();
        $isEdit = $courp !== null;

        if (!$courp) {
            $courp = new Courpublics();
            $courp->setDate(new \DateTime('now'));
        }

        $form = $this->createForm(CourpublicsType::class, $courp, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courp->setDate($form->get('date')->getData());

            $files = $form->get('coursFilesp')->getData();
            foreach ($files as $file) {
                $fichier = $file->getClientOriginalName();
                $file->move(
                    $this->getParameter('cours_directory'),
                    $fichier
                );

                $cfile = new CoursFilesp();
                $cfile->setName($fichier);
                $courp->addCoursFilesp($cfile);
            }
            $this->addFlash('success', 'Votre message est bien ajouté ou modifié');
            $manager->persist($courp);
            $manager->flush();

            return $this->redirectToRoute('app_cour_public');
        }

        return $this->render('cour/addcourspublics.html.twig', [
            'courpublics' => $courpublics,
            'matierepublics' => $matierepublics,
            'coursfilesps' => $files ?? [],
            'formCourspublic' => $form->createView(),
            'EditMode' => $courp->getId() !== null,
        ]);
    }

    #[Route('/addcourspublic/delete/{id}', name: 'app_cour_public_delete')]
    public function deletecourspublic(int $id, ManagerRegistry $doctrine, EntityManagerInterface $manager): Response
    {
        $courp = $doctrine->getRepository(Courpublics::class)->find($id);
        if (!$courp) {
            throw $this->createNotFoundException('Le cours public n\'existe pas');
        }

        $manager->remove($courp);
        $manager->flush();

        $this->addFlash('success', 'Le cours public ' . $courp->getName() . ' a été supprimé');

        return $this->redirectToRoute('app_cour_public');
    }

    #[Route('/pagedescoursp/cours/{id}', name: 'app_page_des_cours')]
    public function pagedescours(Matieres $matieres, ManagerRegistry $doctrine): Response
    {
        $filieres = $doctrine->getRepository(Filieres::class)->findAll();
        $cohortes = $doctrine->getRepository(Classes::class)->findAll();
        $sections = $doctrine->getRepository(Sections::class)->findAll();
        $cours = $doctrine->getRepository(Cours::class)->findAll();

        return $this->render('cour/pagesdecourspriver.html.twig', [
            'matiere' => $matieres,
            'cours' => $cours,
            'filieres' => $filieres,
            'cohortes' => $cohortes,
            'sections' => $sections,
        ]);
    }

    #[Route('/pagedescourspublic/cours/{id}', name: 'app_page_cours_public')]
    public function pagedescoursp(Matierepublics $matierepublics, ManagerRegistry $doctrine): Response
    {
        $courpublics = $doctrine->getRepository(Courpublics::class)->findAll();

        return $this->render('cour/pagedescourspublic.html.twig', [
            'matierepublic' => $matierepublics,
            'courpublics' => $courpublics,
        ]);
    }
}
