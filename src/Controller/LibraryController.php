<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\Shelf;
use App\Entity\Book;
use App\Service\LibraryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/library')]
class LibraryController extends AbstractController
{
    private $libraryService;

    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }

    #[Route('/hello', name: 'hello', methods: ['GET'])]
    public function hello(): Response
    {
        return $this->json(['message' => 'Hello, World!']);
    }

    #[Route('/get', name: 'library_get', methods: ['GET'])]
    public function getAllLibraries(): Response
    {
        $libraries = $this->libraryService->getAllLibraries();
        return $this->json($libraries);
    }

    #[Route('/get/{id}', name: 'library_get', methods: ['GET'])]
    public function getLibraryById(int $id): Response
    {
        $library = $this->libraryService->getLibraryById($id);
        if (!$library) {
            return $this->json(['message' => 'Library not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($library);
    }

    #[Route('/create', name: 'library_create', methods: ['POST'])]
    public function addLibrary(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $library = new Library();
        $library->setName($data['name']);
        $library->setAddress($data['address']);

        foreach ($data['shelves'] as $shelfData) {
            $shelf = new Shelf();
            $shelf->setOrder($shelfData['order']);
            $shelf->setLibrary($library);

            foreach ($shelfData['books'] as $bookData) {
                $book = new Book();
                $book->setName($bookData['name']);
                $book->setAuthorName($bookData['authorName']);
                $book->setShelf($shelf);
                $shelf->getBooks()->add($book);
            }

            $library->getShelves()->add($shelf);
        }

        $this->libraryService->addLibrary($library);
        return $this->json($library, Response::HTTP_CREATED);
    }

    
    #[Route('/update', name: 'library_update', methods: ['PUT'])]
    public function updateLibrary(Request $request, int $id): Response
    {
        $library = $this->libraryService->getLibraryById($id);
        if (!$library) {
            return $this->json(['message' => 'Library not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $library->setName($data['name']);
        $library->setAddress($data['address']);

        // Clear existing shelves and books
        foreach ($library->getShelves() as $shelf) {
            foreach ($shelf->getBooks() as $book) {
                $shelf->getBooks()->removeElement($book);
            }
            $library->getShelves()->removeElement($shelf);
        }

        // Add new shelves and books from data
        foreach ($data['shelves'] as $shelfData) {
            $shelf = new Shelf();
            $shelf->setOrder($shelfData['order']);
            $shelf->setLibrary($library);

            foreach ($shelfData['books'] as $bookData) {
                $book = new Book();
                $book->setName($bookData['name']);
                $book->setAuthorName($bookData['authorName']);
                $book->setShelf($shelf);
                $shelf->getBooks()->add($book);
            }

            $library->getShelves()->add($shelf);
        }

        $this->libraryService->updateLibrary($library);
        return $this->json($library);
    }

   
    #[Route('/delete', name: 'library_delete', methods: ['DELETE'])]
    public function deleteLibrary(int $id): Response
    {
        $library = $this->libraryService->getLibraryById($id);
        if (!$library) {
            return $this->json(['message' => 'Library not found'], Response::HTTP_NOT_FOUND);
        }

        $this->libraryService->deleteLibrary($library);
        return $this->json(['message' => 'Library deleted'], Response::HTTP_NO_CONTENT);
    }
}
