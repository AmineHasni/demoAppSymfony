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

    #[Route('/create', name: 'shelf_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $shelf = $this->shelfService->createShelf($data);

        return new JsonResponse(['status' => 'Shelf created!', 'shelf' => $shelf], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'shelf_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $shelf = $this->shelfService->getShelf($id);

        if (!$shelf) {
            return new JsonResponse(['status' => 'Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($shelf);
    }

    #[Route('/update/{id}', name: 'shelf_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $shelf = $this->shelfService->updateShelf($id, $data);

        if (!$shelf) {
            return new JsonResponse(['status' => 'Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Shelf updated!', 'shelf' => $shelf]);
    }

    #[Route('/delete/{id}', name: 'shelf_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $result = $this->shelfService->deleteShelf($id);

        if (!$result) {
            return new JsonResponse(['status' => 'Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Shelf deleted!']);
    }

    #[Route('/{shelfId}/add-book', name: 'shelf_add_book', methods: ['POST'])]
    public function addBook(int $shelfId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['book'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $book = $this->bookRepository->find($data['book']);

        if (!$book) {
            return new JsonResponse(['status' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $shelf = $this->shelfService->addBookToShelf($shelfId, $book);

        if (!$shelf) {
            return new JsonResponse(['status' => 'Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Book added to shelf!', 'shelf' => $shelf]);
    }

    #[Route('/{shelfId}/remove-book', name: 'shelf_remove_book', methods: ['POST'])]
    public function removeBook(int $shelfId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['book'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $book = $this->bookRepository->find($data['book']);

        if (!$book) {
            return new JsonResponse(['status' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $shelf = $this->shelfService->removeBookFromShelf($shelfId, $book);

        if (!$shelf) {
            return new JsonResponse(['status' => 'Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Book removed from shelf!', 'shelf' => $shelf]);
    }

    #[Route('/{shelfId}/books', name: 'shelf_books', methods: ['GET'])]
    public function getBooksByShelf(int $shelfId): JsonResponse
    {
        $books = $this->shelfService->getBooksByShelfId($shelfId);

        return new JsonResponse($books);
    }
}
