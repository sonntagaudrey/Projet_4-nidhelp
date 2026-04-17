<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Entity\Service;
use App\Form\ExchangeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ExchangeController extends AbstractController
{
    #[Route('/exchange', name: 'app_exchange')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
    
        $receivedExchanges = $entityManagerInterface->getRepository(Exchange::class)->findBy(['receiver' => $user]);
        $sentExchanges = $entityManagerInterface->getRepository(Exchange::class)->findBy(['sender' => $user]);

        
        $exchanges = array_merge($receivedExchanges, $sentExchanges);

        usort($exchanges, function($a, $b) {
            return $b->getCreateDate() <=> $a->getCreateDate();
        });

        return $this->render('exchange/index.html.twig', [
            'exchanges' => $exchanges,
        ]);
    }

    #[Route('/service/{id}/contact', name: 'app_service_contact')]
    public function contact(Service $service, Request $request, EntityManagerInterface $em): Response
    {
        $exchange = new Exchange();
        $form = $this->createForm(ExchangeFormType::class, $exchange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*ajout infos manquantes */
            $exchange->setSender($this->getUser());
            $exchange->setReceiver($service->getAuthor());
            $exchange->setService($service);
            $exchange->setCreateDate(new \DateTime());

            $em->persist($exchange);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('app_service_show', ['id' => $service->getId()]);
        }

        return $this->render('exchange/contact.html.twig', [
            'contactForm' => $form->createView(),
            'service' => $service
        ]);
    }

    
    #[Route('/exchange/{id}/reply', name: 'app_exchange_reply')]
    public function reply(Exchange $exchange, Request $request, EntityManagerInterface $em): Response
    {
        $reply = new Exchange();
        
        $reply->setObject('Re: ' . $exchange->getObject());
        
        $form = $this->createForm(ExchangeFormType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reply->setSender($this->getUser());
            /*ajout infos manquantes */
            $reply->setReceiver($exchange->getSender());
            $reply->setService($exchange->getService());
            $reply->setCreateDate(new \DateTime());

            $em->persist($reply);
            $em->flush();

            $this->addFlash('success', 'Votre réponse a été envoyée !');
            return $this->redirectToRoute('app_exchange');
        }

        return $this->render('exchange/reply.html.twig', [
            'originalExchange' => $exchange,
            'replyForm' => $form->createView(),
        ]);
    }
}
