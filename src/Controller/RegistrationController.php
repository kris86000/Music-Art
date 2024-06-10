<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();     // création d'un nouvel utilisateur
        $form = $this->createForm(RegistrationFormType::class, $user);      // création formulaire pour nouvel utilisateur
        $form->handleRequest($request);     // permet de gérer le traitement de la saisie du formulaire

        if ($form->isSubmitted() && $form->isValid()) {     // permet de savoir si le formulaire est soumis et si il est valide
            // encode the plain password
            $user->setPassword(     // méthode interne permettant de hasher le mot de passe de l'utilisateur
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $entityManager->persist($user);    // demande de permission d'utiliser un stockage persistant et renvoi une promesse si true
            $entityManager->flush();        // push en BDD des données de l'user
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');     // redirection page connexion
        }

        return $this->render('registration/register.html.twig', [       // rendu du template registration
            'registrationForm' => $form->createView(),
        ]);
    }
}
