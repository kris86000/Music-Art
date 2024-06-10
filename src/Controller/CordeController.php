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

class CordeController extends AbstractController
{
    
    #[Route('/corde', name: 'app_cordes')]
    public function afficherCorde(ArticleRepository $repoArticle, PaginatorInterface $paginator, Request $request): Response
    {

        $allCorde = $repoArticle->findBy(['categories' => '3']); // recherche dans la BDD de tous les articles avec la categories 3 //
        // pagination //
        $allCorde = $paginator->paginate(
            $allCorde, // Requête contenant les données à paginer (ici tous nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('cordes/index.html.twig', [ // affichage de ma page cordes //
            'controller_name' => 'CordeController',
            'allCorde' => $allCorde,
        ]);
    }
}
