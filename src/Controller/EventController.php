<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\PurchaseTicket;
use App\Entity\OrderSummary;
use App\Form\EventType;
use App\Form\PurchaseTicketType;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

    #[Route('/events', name: 'events', methods: ['GET'])]
    public function index(EventRepository $eventRepository, TicketRepository $ticketRepository): Response {
        $events = $eventRepository->findLatestEvents();

        foreach ($events as $event) {
            $tickets = $ticketRepository->findBy(['event' => $event->getId()]);
            foreach ($tickets as $ticket) {
                $event->addTicket($ticket);
            }
        }

        dd($events);

        $events = [
            [
                'id' => 1,
                'name' => 'Tomorrow Land 2025 - this is a long string',
                'description' => 'Some quick example text to build on the card title and make up the bulk of the card\'s content.',
                'thumbnail' => 'https://placehold.co/600x400/000000/FFFFFF/png',
                'date' => [
                    'start' => 'Jun 4, 2025',
                    'end' => 'Jun 6, 2025',
                ],
                'time' => [
                    'start' => '10:00',
                    'end' => '22:00',
                ],
                'attendees' => [
                    'total' => 24,
                    'users' => [
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                    ]
                ],
            ],
            [
                'id' => 2,
                'name' => 'Rock in Rio 2025',
                'description' => 'Some quick example text to build on the card title and make up the bulk of the card\'s content.',
                'thumbnail' => 'https://placehold.co/600x400/000000/FFFFFF/png',
                'date' => [
                    'start' => 'Jun 4, 2025',
                    'end' => 'Jun 6, 2025',
                ],
                'time' => [
                    'start' => '10:00',
                    'end' => '22:00',
                ],
                'attendees' => [
                    'total' => 398,
                    'users' => [
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                    ]
                ],
            ],
            [
                'id' => 3,
                'name' => 'Lollapalooza 2025',
                'description' => 'Some quick example text to build on the card title and make up the bulk of the card\'s content.',
                'thumbnail' => 'https://placehold.co/600x400/000000/FFFFFF/png',
                'date' => [
                    'start' => 'Jun 4, 2025',
                    'end' => 'Jun 6, 2025',
                ],
                'time' => [
                    'start' => '10:00',
                    'end' => '22:00',
                ],
                'attendees' => [
                    'total' => 288,
                    'users' => [
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                        [ 'avatar' => 'https://placehold.co/600x400/000000/FFFFFF/png'],
                    ],
                ],
            ],
        ];

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/{id}', name: 'events_show', methods: ['GET', 'POST'])]
    public function show(
        int $id,
        EventRepository $eventRepository,
        TicketRepository $ticketRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('The event does not exist');
        }

        $tickets = $ticketRepository->findBy(['event' => $id]);
        foreach ($tickets as $ticket) {
            $event->addTicket($ticket);
        }

        $purchaseTicket = new PurchaseTicket();

        $form = $this->createForm(PurchaseTicketType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantiy = $form->get('quantity')->getData();

            // Check if there are enough tickets available
            $availableTickets = $ticketRepository->findAvailableTicket($id);
            if ($availableTickets < $quantiy) {
                $this->addFlash('errro', 'There are not enough tickets available');
                return $this->redirectToRoute('events_show', ['id' => $id]);
            }

            // Update purchase_ticket and ticket tables
            $ticket->setSold($ticket->getSold() + $quantiy);

            $purchaseTicket->setTicket($ticket);
            $purchaseTicket->setQuantity($quantiy);
            $entityManager->persist($purchaseTicket);

            // Insert into order_summary table
            $orderSummary = new OrderSummary();
            $orderSummary->setTotalAmount($ticket->getPrice() * $quantiy);
            $orderSummary->setCurrency($ticket->getCurrency());
            $orderSummary->setDiscount(0.0);
            $orderSummary->setServiceFee(0.0);
            $orderSummary->setTicket($ticket);
            $orderSummary->setPurchaseTicket($purchaseTicket);
            $entityManager->persist($orderSummary);

            $entityManager->flush();

            return $this->redirectToRoute('order_summary', [
                'orderSummaryId' => $orderSummary->getId(),
            ]);
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/events/create', name: 'events_create', methods: ['GET', 'POST'])]
    public function dashboardCreateEvents(Request $request, EntityManagerInterface $entityManager): Response {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('dashboard/events.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
