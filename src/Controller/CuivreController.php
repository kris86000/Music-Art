<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Article;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;



class CuivreController extends AbstractController
{
    #[Route('/cuivre', name: 'app_cuivres')]
    public function afficherCuivre(ArticleRepository $repoArticle, PaginatorInterface $paginator, Request $request): Response
    {
        $allCuivre = $repoArticle->findBy(['categories' => '1']); // recherche dans la BDD de tous les articles avec la categories 1 //

         // pagination //
        $allCuivre = $paginator->paginate(
            $allCuivre, // Requête contenant les données à paginer (ici tous nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('cuivres/index.html.twig', [ // affichage de ma page cuivres //
            'controller_name' => 'CuivreController',
            'allCuivre' => $allCuivre,
        ]);
    }
    
}


