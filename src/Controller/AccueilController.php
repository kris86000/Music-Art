<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\Commande;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class AccueilController extends AbstractController
{
        
        #[Route('/', name: 'app_accueil')]
        public function index(ArticleRepository $repoArticle, PaginatorInterface $paginator, Request $request): Response
        {
            $articles = $repoArticle->findAll();  // repository pour rechercher tous les articles dans la BDD
            
            // pagination //
            $articles = $paginator->paginate(
                $articles, // Requête contenant les données à paginer (ici tous nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                6 // Nombre de résultats par page
            );
            return $this->render('accueil/index.html.twig', [ // rendu sur la page accueil
                'articles' => $articles,
            ]);
        }
    
        #[Route('/detailArticle/{id}', name: 'app_article_details')]  // vue detaillée de l'article
        public function show(?Article $articles): Response
        {
            
            if(!$articles){
                return $this->redirectToRoute("accueil");
            }
    
            return $this->render("accueil/single_article.html.twig",[
                'articles' => $articles
            ]);
        }

}
