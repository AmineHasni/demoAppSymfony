<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BookService;
use Symfony\Component\HttpFoundation\Request;

#[Route('/book')]
class BookController extends AbstractController
{
    private $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    
}
