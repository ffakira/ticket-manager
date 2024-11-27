<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author ffakira
 * @date 27/11/2024
 * @description Verify QR Code generated
 */
class TicketVerifyController extends AbstractController {
    #[Route('/api/ticket/verify', name: 'api_ticket_verify', methods: ['POST'])]
    public function verifyQrCode(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['value'])) {
            return $this->json([
                'status' => 400,
                'data' => false,
                'error' => [
                    'message' => 'Invalid data request',
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $decryptedData = $this->decryptData($data['value']);
        $isValid = $this->verifyEncryptedData($decryptedData);

        if (!$isValid) {
            return $this->json([
                'status' => 401,
                'data' => false,
                'error' =>  [
                    'message' => 'Invalid data'
                ],
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'status' => 200,
            'data' => true,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @TODO update order_summary ticket to include the
     * generated qr code
     */
    private function decryptData(string $encryptedData): string {
        $key = $this->getParameter('qr_code_ecnryption_key');
        $alg = 'aes-256-cbc';

        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($alg);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $alg, $key, 0, $iv);
    }

    /**
     * @TODO implement the verification for each QR Code.
     */    
    private function verifyEncryptedData(string $decryptedData): bool {
        return false;
    }
}
