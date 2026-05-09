<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotController extends AbstractController
{
    public function __construct(private HttpClientInterface $httpClient) {}

    #[Route('/chatbot', name: 'chatbot', methods: ['POST'])]
    public function chat(Request $request): JsonResponse
    {
        $data    = json_decode($request->getContent(), true);
        $message = trim($data['message'] ?? '');

        if (empty($message)) {
            return new JsonResponse(['reply' => 'Veuillez entrer un message.'], 400);
        }

        $apiKey = $_ENV['ANTHROPIC_API_KEY'] ?? '';

        if (empty($apiKey)) {
            return new JsonResponse(['reply' => $this->fallbackReply($message)]);
        }

        try {
            $response = $this->httpClient->request('POST', 'https://api.anthropic.com/v1/messages', [
                'headers' => [
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ],
                'json' => [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => 300,
                    'system'     => 'Tu es l\'assistant virtuel d\'EcoRide, une application de covoiturage écologique française. Tu réponds uniquement en français, de façon concise (max 3 phrases). Tu aides les utilisateurs à trouver des trajets, comprendre le système de crédits, proposer un covoiturage, ou toute question sur l\'app. Si la question n\'est pas liée à EcoRide, redirige poliment vers les fonctionnalités de l\'app.',
                    'messages'   => [['role' => 'user', 'content' => $message]],
                ],
            ]);

            $body  = $response->toArray();
            $reply = $body['content'][0]['text'] ?? 'Désolé, je n\'ai pas pu répondre.';

            return new JsonResponse(['reply' => $reply]);
        } catch (\Throwable) {
            return new JsonResponse(['reply' => $this->fallbackReply($message)]);
        }
    }

    private function fallbackReply(string $message): string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'crédit') || str_contains($msg, 'credit')) {
            return 'Les crédits EcoRide vous permettent de réserver des trajets. Chaque réservation coûte 1 crédit. Vous pouvez en obtenir en proposant vos propres trajets.';
        }
        if (str_contains($msg, 'trajet') || str_contains($msg, 'covoiturag')) {
            return 'Pour proposer un trajet, connectez-vous puis cliquez sur "Proposer un trajet" dans votre profil. Votre voiture doit être enregistrée au préalable.';
        }
        if (str_contains($msg, 'réserv') || str_contains($msg, 'reserv') || str_contains($msg, 'particip')) {
            return 'Pour participer à un trajet, trouvez-le via la recherche, puis cliquez sur "Participer". 1 crédit sera débité.';
        }
        if (str_contains($msg, 'co2') || str_contains($msg, 'écolog') || str_contains($msg, 'ecolog')) {
            return 'Chaque trajet partagé économise en moyenne 2,1 kg de CO₂. Les véhicules électriques sont signalés par le badge "Écologique".';
        }
        if (str_contains($msg, 'inscri') || str_contains($msg, 'compte') || str_contains($msg, 'register')) {
            return 'Créez votre compte gratuitement en cliquant sur "Mon compte" puis "S\'inscrire". Vous recevrez des crédits de bienvenue !';
        }
        if (str_contains($msg, 'bonjour') || str_contains($msg, 'salut') || str_contains($msg, 'hello')) {
            return 'Bonjour ! Je suis l\'assistant EcoRide. Je peux vous aider sur les trajets, les crédits, ou l\'inscription. Que puis-je faire pour vous ?';
        }

        return 'Je suis là pour vous aider avec EcoRide ! Posez-moi des questions sur la recherche de trajets, les crédits, ou comment proposer un covoiturage.';
    }
}
