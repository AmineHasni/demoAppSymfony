<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


use App\Entity\Shelf;
use App\Service\LibraryService;
use Symfony\Component\HttpFoundation\Request;


#[Route('/library')]
class LibraryController extends AbstractController
{
    private $libraryService;

    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }

    #[Route('/create', name: 'library_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['name'], $data['address'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $library = $this->libraryService->createLibrary($data);

        return new JsonResponse(['status' => 'Library created!', 'library' => $library], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'library_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $library = $this->libraryService->getLibrary($id);

        if (!$library) {
            return new JsonResponse(['status' => 'Library not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($library);
    }

    #[Route('/update/{id}', name: 'library_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $library = $this->libraryService->updateLibrary($id, $data);

        if (!$library) {
            return new JsonResponse(['status' => 'Library not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Library updated!', 'library' => $library]);
    }

    #[Route('/delete/{id}', name: 'library_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $result = $this->libraryService->deleteLibrary($id);

        if (!$result) {
            return new JsonResponse(['status' => 'Library not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Library deleted!']);
    }

    #[Route('/{libraryId}/add-shelf/{shelfId}', name: 'library_add_shelf', methods: ['POST'])]
    public function addShelf(int $libraryId, int $shelfId): JsonResponse
    {
        $library = $this->libraryService->getLibrary($libraryId);
        $shelf = $this->libraryService->getShelfById($shelfId);

        if (!$library || !$shelf) {
            return new JsonResponse(['status' => 'Library or Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $result = $this->libraryService->addShelfToLibrary($libraryId, $shelf);

        if (!$result) {
            return new JsonResponse(['status' => 'Failed to add shelf to library'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['status' => 'Shelf added to library!', 'library' => $result]);
    }

    #[Route('/{libraryId}/remove-shelf/{shelfId}', name: 'library_remove_shelf', methods: ['DELETE'])]
    public function removeShelf(int $libraryId, int $shelfId): JsonResponse
    {
        $library = $this->libraryService->getLibrary($libraryId);
        $shelf = $this->libraryService->getShelfById($shelfId);

        if (!$library || !$shelf) {
            return new JsonResponse(['status' => 'Library or Shelf not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $result = $this->libraryService->removeShelfFromLibrary($libraryId, $shelf);

        if (!$result) {
            return new JsonResponse(['status' => 'Failed to remove shelf from library'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['status' => 'Shelf removed from library!', 'library' => $result]);
    }

    #[Route('/{libraryId}/shelves', name: 'library_shelves', methods: ['GET'])]
    public function getShelvesByLibrary(int $libraryId): JsonResponse
    {
        $shelves = $this->libraryService->getShelvesByLibraryId($libraryId);

        return new JsonResponse($shelves);
    }
}
