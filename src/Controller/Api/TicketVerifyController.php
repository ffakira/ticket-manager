<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author ffakira
 * @date 27/11/2024
 * @description Verify QR Code generated
 */
class TicketVerifyController extends AbstractController {
    public function verifyQrCode(): JsonResponse {
        return $this->json([
            'test' => 'test',
        ]);
    }
}