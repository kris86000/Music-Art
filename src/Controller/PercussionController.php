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

class PercussionController extends AbstractController
{
    #[Route('/percussion', name: 'app_percussions')]
    public function afficherPercussion(ArticleRepository $repoArticle, PaginatorInterface $paginator, Request $request): Response
    {
        $allPercussion = $repoArticle->findBy(['categories' => '2']);

        // pagination //
        $allPercussion = $paginator->paginate(
            $allPercussion, // Requête contenant les données à paginer (ici tous nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('percussions/index.html.twig', [
            'controller_name' => 'PercussionController',
            'allPercussion' => $allPercussion,
        ]);
    }
}