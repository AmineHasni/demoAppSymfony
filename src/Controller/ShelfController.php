<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ShelfService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;

#[Route('/shelf')]
class ShelfController extends AbstractController
{
    private $shelfService;
    private $bookRepository;

    public function __construct(ShelfService $shelfService, BookRepository $bookRepository)
    {
        $this->shelfService = $shelfService;
        $this->bookRepository = $bookRepository;
    }

    
}
