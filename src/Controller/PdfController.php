<?php

namespace App\Controller;

use App\Repository\OrderSummaryRepository;
use Knp\Snappy\Pdf;
use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author ffakira
 * @date 24/11/2024
 * @description: Generate PDF file for event tickets
 */
class PdfController extends AbstractController {
    private Pdf $pdf;
    private BuilderInterface $qrCodeBuilder;

    public function __construct(Pdf $pdf, BuilderInterface $qrCodeBuilder) {
        $this->pdf = $pdf;
        $this->qrCodeBuilder = $qrCodeBuilder;
    }

    #[Route('/order-summary/{orderSummaryId}', name: 'order_summary', methods: ['GET'])]
    public function orderSummary(
        int $orderSummaryId,
        OrderSummaryRepository $orderSummaryRepository,
    ): Response {
        $orderSummary = $orderSummaryRepository->find($orderSummaryId);

        $ticket = $orderSummary->getTicket();
        $purchaseTicket = $orderSummary->getPurchaseTicket();
        $event = $ticket->getEvent();

        if (!$orderSummary) {
            throw $this->createNotFoundException('Order summary not found');
        }

        $currencyLabel = $this->currencyLabelFormat($orderSummary->getCurrency());

        $orderSummary = [
            'total_amount' => [
                'price' => $orderSummary->getTotalAmount(),
                'currency' => $currencyLabel,
            ],
            'discount' => [
                'price' => $orderSummary->getDiscount(),
                'currency' => $currencyLabel,
            ],
            'service_fee' => [
                'price' => $orderSummary->getServiceFee(),
                'currency' => $currencyLabel,
            ],
        ];

        $purchaseTicketQuantity = $purchaseTicket->getQuantity();

        // @TODO Move ticket generation to utility helper function
        $tickets = [];
        for ($i = 0; $i < $purchaseTicketQuantity; $i++) {
            // Encrypt unique qr code date
            $encrypData = $this->encryptData(json_encode([
                'orderSummaryId' => $orderSummaryId,
                'ticketId' => $ticket->getId(),
                'purchaseTicketId' => $purchaseTicket->getId(),
                'ticketNumber' => $i,
            ]));

            $tickets[] = [
                'name' => $ticket->getName(),
                'price' => $ticket->getPrice(),
                'currency' => $currencyLabel,
                'bookingId' => $orderSummaryId . '-' . $i,
                'qrCode' => $this->generateQrCode($encrypData),
            ];
        }

        // @TODO Move date and time to utility helper function
        $event = [
            'eventId' => 29891,
            'name' => $event->getName(),
            'date' => [
                'start' => date('M j, Y', $event->getStartDate()->getTimestamp()),
                'end' => date('M j, Y', $event->getEndDate()->getTimestamp()),
            ],
            'time' => [
                'start' => date('H:i', $event->getStartTime()->getTimestamp()),
                'end' => date('H:i', $event->getEndTime()->getTimestamp()),
            ],
            'location' => $event->getLocation(),
        ];

        $content = $this->renderView('pdf/booking_details-template.html.twig', [
            'order_status' => 'Completed',
            'order_payment_method' => 'PayPay',
            'order_summary' => $orderSummary,
            'tickets' => $tickets,
            'event' => $event,
        ]);
        $pdfContent = $this->pdf->getOutputFromHtml($content, [
            'title' => $event['name'] . ' #' . (string)$event['eventId'],
        ]);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $event['eventId'] . '.pdf',
        ]);
    }

    private function generateQrCode(string $data): string {
        $qrCode = $this->qrCodeBuilder->build(
            data: $data,
            size: 150,
            margin: 10,
        );

        return $qrCode->getDataUri();
    }

    private function currencyLabelFormat(string $currency): string {
        return match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'BRL' => 'R$',
            'JPY' => '¥',
            default => $currency,
        };
    }

    /**
     * @TODO include a random hash, to verify the data have
     * not been tampered via the database
     */
    private function encryptData(string $data): string {
        $key = $this->getParameter('qr_code_encryption_key');
        $alg = 'aes-256-cbc';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($alg));
        $encrypted = openssl_encrypt($data, $alg, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
}
