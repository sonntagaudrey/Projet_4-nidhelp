<?php

namespace App\Controller;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Routing\Attribute\Route; 

class MailerController extends AbstractController{
    
    #[Route('/mail', name: 'app_test_mail')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
        ->from('mailtrap@example.com')
        ->to('alex@example.com')
        ->subject('Bienvenu sur Symfony !')
        ->htmlTemplate('emails/test.html.twig')
        ->context([
        'name' => 'Alex'
        ]);
        $mailer->send($email);

        return $this->redirectToRoute('app_home');
    }
}