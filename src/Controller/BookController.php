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

    #[Route('/create', name: 'book_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $book = $this->bookService->createBook($data);
        return new JsonResponse(['status' => 'Book created!', 'book' => $book], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'book_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $book = $this->bookService->getBook($id);
        if (!$book) {
            return new JsonResponse(['status' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse($book);
    }

    #[Route('/update/{id}', name: 'book_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $book = $this->bookService->updateBook($id, $data);
        if (!$book) {
            return new JsonResponse(['status' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['status' => 'Book updated!', 'book' => $book]);
    }

    #[Route('/delete/{id}', name: 'book_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $result = $this->bookService->deleteBook($id);
        if (!$result) {
            return new JsonResponse(['status' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['status' => 'Book deleted!']);
    }

    #[Route('/shelf/{shelfId}', name: 'books_by_shelf', methods: ['GET'])]
    public function getByShelf(int $shelfId): JsonResponse
    {
        $books = $this->bookService->getBooksByShelfId($shelfId);
        return new JsonResponse($books);
    }
}
