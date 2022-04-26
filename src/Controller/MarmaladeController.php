<?php

namespace App\Controller;

use App\Services\RegistrationService;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MarmaladeController extends AbstractController
{
    /**
     * @var \App\Services\RegistrationService
     */
    private RegistrationService $service;

    /**
     * @param \App\Services\RegistrationService $regService
     */
    public function __construct(RegistrationService $regService)
    {
        $this->service = $regService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resolveRegistrationFromRequest(Request $request): JsonResponse
    {
        try {
            $resolveRequest = $this->service->resolve($request->toArray());
        } catch (Exception) {
            $resolveRequest = ['failed'];
        }

        return $this->json($resolveRequest);
    }
}
