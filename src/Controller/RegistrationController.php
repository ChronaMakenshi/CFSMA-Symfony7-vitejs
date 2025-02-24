<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Entity\Users;
use App\Entity\Sections;
use App\Entity\Compagnies;
use App\Entity\Filieres;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use App\Form\RegistrationFormateurFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RegistrationStagiaireFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/admin', name: 'admin_')]
class RegistrationController extends AbstractController
{
    #[Route('/addUsers/', name: 'app_register')]
    public function register(Users $user = null, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $use, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $users = $use->getRepository(Users::class)->findAll();
        $compagnies = $use->getRepository(Compagnies::class)->findAll();
        $sections = $use->getRepository(Sections::class)->findAll();

        $user = new Users();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getId()) {
                $user;
            }
            $this->addFlash('success', 'Votre messsage est bien ajouté ou modifié');
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_app_register');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/register.html.twig', [
            'users' => $users,
            'compagnies' => $compagnies,
            'sections' => $sections,
            'registrationForm' => $form->createView(),
            'EditMode' => $user->getId() !== null,
        ]);
    }

    #[Route('/addUsers/modifier/{id}', name: 'app_register_edit')]
    public function registeredit(Users $user = null, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $use, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $users = $use->getRepository(Users::class)->findAll();
        $compagnies = $use->getRepository(Compagnies::class)->findAll();
        $sections = $use->getRepository(Sections::class)->findAll();
        if (!$user) {
            $user = new Users();
        }
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getId()) {
                $user;
            }
            $this->addFlash('success', 'Votre messsage est bien ajouté ou modifié');
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('admin_app_register');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/edituser.html.twig', [
            'users' => $users,
            'compagnies' => $compagnies,
            'sections' => $sections,
            'EditUserType' => $form->createView(),
            'EditMode' => $user->getId() !== null,
        ]);
    }

    #[Route("/addUsers/delete/{id}", name: "app_register_delete")]
    public function deleteuser(int $id, Users $user, ManagerRegistry $use, EntityManagerInterface $manager): Response
    {
        $user = $use->getRepository(Users::class)->find($id);
        $this->addFlash('success', 'Votre messsage est supprimé !');
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_app_register');
    }

    #[Route('/addFormateurs/', name: 'app_formateur')]
    #[Route('/addFormateurs/modifier/{id}', name: 'app_formateur_edit')]

    public function registerformateur(Users $user = null,Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, ManagerRegistry $use): Response
    {
        $users = $use->getRepository(Users::class)->findAll();
        $filieres = $use->getRepository(Filieres::class)->findAll();
        $sections = $use->getRepository(Sections::class)->findAll();
        if(!$user ){
            $user = new Users();
        }
        $form = $this->createForm(RegistrationFormateurFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if(!$user->getId()){
                $user;
            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('admin_app_formateur');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/addformateur.html.twig', [
            'registrationForm' => $form->createView(),
            'users' =>  $users,
            'filieres' =>  $filieres,
            'sections' =>  $sections,
            'EditMode' =>  $user->getId() !== null,
        ]);
    }

    #[Route('/addFormateurs/delete/{id}', name: 'app_formateur_delete')]

    public function deleteformateur(int $id, Users $user, ManagerRegistry $use, EntityManagerInterface $manager): Response
    {
        $user = $use->getRepository(Users::class)->find($id);
        $this->addFlash('success', 'Votre messsage est supprimé !');
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_app_formateur');
    }

    #[Route('/addStagiaires/', name: 'app_stagiaires')]
    #[Route('/addStagiaires/modifier/{id}', name: 'app_stagiaires_edit')]
    public function registerstagiaire(
        Users $user = null,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        ManagerRegistry $use
    ): Response {
        // Récupération des données nécessaire pour le template
        $users = $use->getRepository(Users::class)->findAll();
        $sections = $use->getRepository(Sections::class)->findAll();
        $filieres = $use->getRepository(Filieres::class)->findAll();
        $cohortes = $use->getRepository(Classes::class)->findAll();

        if (!$user) {
            $user = new Users();
        }

        // Passer l'utilisateur authentifié comme option
        $authenticatedUser = $this->getUser();

        $form = $this->createForm(RegistrationStagiaireFormType::class, $user, [
            'user' => $authenticatedUser,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe en clair si l'utilisateur est nouveau
            if (!$user->getId()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Les informations ont été enregistrées.');

            // Redirection vers la liste des stagiaires
            return $this->redirectToRoute('admin_app_stagiaires');
        }

        return $this->render('registration/addstagiaire.html.twig', [
            'registrationForm' => $form->createView(),
            'users' => $users,
            'sections' => $sections,
            'filieres' => $filieres,
            'cohortes' => $cohortes,
            'EditMode' => $user->getId() !== null,
        ]);
    }

    #[Route('/addStagiaires/delete/{id}', name: 'app_stagiaires_delete')]
    public function deletestagiaire(
        int $id,
        ManagerRegistry $use,
        EntityManagerInterface $manager
    ): Response {
        $user = $use->getRepository(Users::class)->find($id);

        if ($user) {
            $this->addFlash('success', 'Le stagiaire a été supprimé avec succès !');
            $manager->remove($user);
            $manager->flush();
        } else {
            $this->addFlash('error', 'Le stagiaire n\'a pas été trouvé.');
        }

        return $this->redirectToRoute('admin_app_stagiaires');
    }
}
