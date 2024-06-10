<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticleRepository;

class CartController extends AbstractController
{

    // interface du panier
    #[Route('/cart', name: 'cart_index')]
    public function panier(SessionInterface $session, ArticleRepository $articleRepository): Response
    {   
        $panier = $session->get('panier', []);  // récupération du panier

        $dataPanier = [];  // element dans le panier

        foreach ($panier as $id => $quantity) {  // pour chaque elemnt du panier, je récupere l'id de l'article et la quantité //
            $dataPanier[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity,
            ];
        }

        $totalHt = 0;    // initialisation des variable a 0
        $quantity_cart = 0;
        $totalTTC = 0;
        $tva = 19.5;

        foreach ($dataPanier as $item) {  // pour chaque item dans element du panier
            $totalItem = $item['article']->getPrice() * $item['quantity']; // calcul prix article * quantité
            $totalHt += $totalItem;     // total Hors Taxe = addition du prix calculé sur la ligne au dessus
            $quantity_cart += $item['quantity']; // quantité totale d'article dans le panier
            $totalTTC = (($totalHt * $tva) / 100) + $totalHt;  // calcul pour prix TTC
        }

        $session->set('quantity_cart', $quantity_cart);  // set quantité totale
        $session->set('montant', $totalHt);     // set montant total commande
        $session->set('Tva', $tva);     // set de la tva    
        $session->set('totalTTC', $totalTTC);       // set prix total TTC
        $session->set('panier', $panier);      // set du panier avec nouvelles valeures
        
        return $this->render('cart/index.html.twig', [ // rendu avec les variables pour la page panier
            'items' => $dataPanier,
            'totalHt' => $totalHt,
            'quantity_cart' => $quantity_cart,
            'tva' => $tva,
            'totalTTC' => $totalTTC,
        ]);

    }
    
        // ajouter au panier
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);      // recup du panier

        if (!empty($panier[$id])) {     // si l'article est deja dans le panier on rajoute 1
            $panier[$id]++;
        } else {        // sinon on l'ajoute
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);       // set du panier avec nouvelle valeure

        $totalArticle = array_sum($panier);  // Calcule la somme des valeurs du tableau

        $session->set('totalArticle', $totalArticle);   // set de cette valeur dans totalArticle

        return $this->redirectToRoute('app_accueil');  // redirection page d'accueil

    }

    // ajouter un article via page panier avec le bouton + 
    #[Route('/cart/plus/{id}', name: 'cart_plus')]
    public function plus($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);      // meme code que celui du dessus mais avec redirection sur la page panier

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        $totalArticle = array_sum($panier);

        $session->set('totalArticle', $totalArticle);
        
        return $this->redirectToRoute('cart_index');

    }

    // suppression d'un article via page panier avec le bouton -
    #[Route('/cart/moins/{id}', name: 'cart_moins')]
    public function moins($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);      // recup panier

        if (!empty($panier[$id] > 1)) {     // si l'article est deja dans le panier et que la quantité est supérieure a 1 alors on enleve 1 a la quantité
            $panier[$id]--;
        } else {        // sinon on supprime la ligne article
            unset($panier[$id]);
        }

        $session->set('panier', $panier);       // set du panier avec nouvelle valeure

        $totalArticle = array_sum($panier);

        $session->set('totalArticle', $totalArticle);

        return $this->redirectToRoute('cart_index');        // redirection page panier

    }

    // delete ligne du panier via bouton corbeille
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session)
    {       
        $panier = $session->get('panier', []);  // recup du panier

        if (!empty($panier[$id])) {     // si la ligne article existe alors on supprime la ligne article
            unset($panier[$id]);
        }

        $session->set('panier', $panier);       // set du panier avec nouvelle valeure

        $totalArticle = array_sum($panier);

        $session->set('totalArticle', $totalArticle);

        return $this->redirectToRoute('cart_index');    // redirection page panier
    }

    // delete panier total
    #[Route('/cart/delete', name: 'cart_delete')]
    public function deletePanier( SessionInterface $session)
    {       
        $panier = $session->get('panier', []);  // recup du panier

        $panier = [];

        $session->set('panier', $panier);

        $totalArticle = array_sum($panier);

        $session->set('totalArticle', $totalArticle);

        return $this->redirectToRoute('cart_index');    // redirection page panier
    }
}
