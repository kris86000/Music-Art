<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new Email())  
                ->from(new Address($data['email'], $data['nom'] . ' '. $data['prenom']))
                ->to('kris.dev.test@gmail.com')
                ->replyTo($data['email'])
                ->subject($data['demande'])
                ->text($data['message']);
            $mailer->send($email);
            return $this->redirectToRoute('app_accueil');
        } 

        return $this->render('contact/index.html.twig', [
            'contactType' => $form->createView(),
        ]);
    }
}
