<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{
        #[Route('/search', name: 'app_search')]
        public function search(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator)
    {
        $searchArticle = $request->get('searchArticle'); // récupération de l'input search

        // Récupérer les article en fonction du nameArticle
        $articleTrouve = $articleRepository->findByNameArticle($searchArticle); // méthode de articleRepository

        // pagination //
        $articleTrouve = $paginator->paginate(
            $articleTrouve, // Requête contenant les données à paginer (ici tous nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('search/index.html.twig', [
            'articleTrouve' => $articleTrouve,
            'searchArticle' => $searchArticle
        ]);
    }
}
