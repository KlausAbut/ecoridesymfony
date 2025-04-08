<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserCreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/credits', name: 'api_credits_')]
class CreditController extends AbstractController
{
    public function __construct(
        private UserCreditRepository $creditRepo
    ) {}

    #[Route('/user/{id}/add', methods: ['POST'])]
    public function addCredit(User $user, Request $request): JsonResponse
    {
        $amount = $request->request->getInt('amount');
        
        $this->creditRepo->updateBalance($user->getId(), $amount);

        return $this->json([
            'success' => true,
            'newBalance' => $user->getCredit()->getAmount()
        ]);
    }
}