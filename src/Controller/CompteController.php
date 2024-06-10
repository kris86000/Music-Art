<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;


class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]  
    public function index(): Response
    {
        return $this->render('compte/index.html.twig', [
        ]);
    }

    #[Route('/modification', name: 'app_modif_compte')]
    public function modifCompte(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $modifCompte = $this->createFormBuilder($user)
            ->add('prenomUser', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('nomUser', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('codePostal', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->getForm();

        $modifCompte->handleRequest($request);

        if ($modifCompte->isSubmitted() && $modifCompte->isValid()) {
            $user = $modifCompte->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/modification.html.twig', [
            'modifCompte' => $modifCompte->createView(),
        ]);
    }
}
