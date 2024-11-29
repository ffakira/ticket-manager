<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Event;
use App\Form\TicketType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController {
    #[Route('/dashboard/tickets/create', name: 'ticket_create', methods: ['GET', 'POST'])]
    public function dashboardCreateTickets(Request $request, EntityManagerInterface $entityManager): Response {
        $ticket = new Ticket();
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a ticket');
        }

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventId = $form->get('event')->getData();
            $event = $entityManager->getRepository(Event::class)->find($eventId);

            if (!$event) {
                return $this->redirectToRoute('ticket_create');
            }

            $ticket->setEvent($event);
            $ticket->setUser($user);

            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('dashboard/ticket.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
