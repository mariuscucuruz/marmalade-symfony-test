<?php

namespace App\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json(Kernel::VERSION);
    }
}
