<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Repository\CommandeRepository;
use App\Repository\DetailCommandeRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CommandeController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/commande', name: 'app_commande')]
    public function addCommande(ManagerRegistry $doctrine, AuthenticationUtils $authenticationUtils): Response
    {
        $session = $this->requestStack->getSession(); // récupération de la session
    
        $entityManager = $doctrine->getManager(); // entityManager pour manipuler les données
        $articleRepository = $doctrine->getRepository(Article::class);  // repository article
        $userRepository = $doctrine->getRepository(User::class);     // repository user

        $lastNomUser = $authenticationUtils->getLastUsername();  // récupération de l' user authentifié
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $lastNomUser]); // user = recup de l'email de l'user authentifié

        $panier = $session->get('panier',[]);  // récupération du panier enregitré en session

        $montant = $session->get('totalTTC');  // récupération du montant total toute taxe comprise
        $session->set('totalTTC', $montant);  // on set le montant

        $commande = new Commande(); // nouvelle commande
        $commande->setMontant($montant);  // set du montant de la commande par rapport au panier
        $dateHeureActuel = new \DateTimeImmutable; // instanciation de la date de commande au format indiqué //
                $dateHeureActuel->setDate(date('Y'), date('m'), date('d')); // format date
                $dateHeureActuel->setTime(date('H'), date('i'), date('s')); // format heure
                $dateHeureActuel->format("Y-m-d H:i:s");
        $commande->setDateCommande($dateHeureActuel);  // set de la date
        $commande->setUser($user);  // set de l'user

        $entityManager->persist($commande);  
        $entityManager->flush();  // push de la commande en BDD 
        
        foreach( $panier as $id => $quantity) {            // pour chaque element dans le panier avec clé id => value quantité
            $detailCommande = new DetailCommande();         // nouveau DetailCommande
            $detailCommande->setQuantity($quantity);        // set de la quantité
            $detailCommande->setArticle($articleRepository->find($id));  // recherche de l'article par rapport a son id
            $detailCommande->setCommande($commande);        // set de la commande
            // retrait commande du stock par rapport a l'id article
            $article = $articleRepository->find($id);
            $article->setStock($article->getStock() - $quantity);
            $entityManager->persist($detailCommande);       
        }
        $entityManager->flush();
        //vider le panier quand commande effectuée //
        $panier = [];

        $totalArticle = array_sum($panier);     // Calcule la somme des valeurs du tableau

        $session->set('totalArticle', $totalArticle); // je set cette valeure

        $session->set('panier', $panier);


        return $this->redirectToRoute('app_confirmation');
    }

    // résumé de la commande sur un twig autre que le traitement de la commande //
    #[Route('/confirmation', name: 'app_confirmation')]
    public function resumeCommande(ManagerRegistry $doctrine){
        // code resume commande//
        $commandeRepository = $doctrine->getRepository(Commande::class); // récuperation de la commande entrée en BDD
        $lastCommande = $commandeRepository->findOneBy([], ['dateCommande' => 'DESC']); // récupération de la derniere commande par rapport a la date
        
        //dd($lastCommande);
        $detailCommandeRepository = $doctrine->getRepository(DetailCommande::class); // récupération des DetailCommande entrée en BDD
        $details = $detailCommandeRepository->findBy(['commande' => $lastCommande]);    // récupération des détails par rapport a la commande du dessus
        //dd($details);

        $montant = $lastCommande->getMontant(); // récupéré le montant total de la commande //

        return $this->render('commande/confirmation.html.twig', [  // retour template commande
            'details' => $details,
            'montant' => $montant
        ]);
    }   
}
