<?php
namespace App\Service;

use App\Entity\Library;
use App\Entity\Shelf;
use App\Repository\LibraryRepository;
use App\Repository\ShelfRepository;
use Doctrine\ORM\EntityManagerInterface;

class LibraryService
{
    private $libraryRepository;
    private $entityManager;
    private $shelfRepository;

    public function __construct(LibraryRepository $libraryRepository, ShelfRepository $shelfRepository, EntityManagerInterface $entityManager)
    {
        $this->libraryRepository = $libraryRepository;
        $this->entityManager = $entityManager;
        $this->shelfRepository = $shelfRepository;
    }

    public function createLibrary(array $data): Library
    {
        $library = new Library();
        $library->setName($data['name']);
        $library->setAddress($data['address']);

        $this->entityManager->persist($library);
        $this->entityManager->flush();

        return $library;
    }

    public function updateLibrary(int $id, array $data): ?Library
    {
        $library = $this->libraryRepository->find($id);

        if (!$library) {
            return null;
        }

        $library->setName($data['name'] ?? $library->getName());
        $library->setAddress($data['address'] ?? $library->getAddress());

        $this->entityManager->flush();

        return $library;
    }

    public function deleteLibrary(int $id): bool
    {
        $library = $this->libraryRepository->find($id);

        if (!$library) {
            return false;
        }

        $this->entityManager->remove($library);
        $this->entityManager->flush();

        return true;
    }

    public function getLibrary(int $id): ?Library
    {
        return $this->libraryRepository->find($id);
    }

    public function addShelfToLibrary(int $libraryId, Shelf $shelf): ?Library
    {
        $library = $this->libraryRepository->find($libraryId);

        if (!$library) {
            return null;
        }

        $library->addShelf($shelf);
        $this->entityManager->flush();

        return $library;
    }

    public function removeShelfFromLibrary(int $libraryId, Shelf $shelf): ?Library
    {
        $library = $this->libraryRepository->find($libraryId);

        if (!$library) {
            return null;
        }

        $library->removeShelf($shelf);
        $this->entityManager->flush();

        return $library;
    }

    public function getShelvesByLibraryId(int $libraryId): array
    {
        $library = $this->libraryRepository->find($libraryId);

        if (!$library) {
            return [];
        }

        return $library->getShelves()->toArray();
    }
    public function getShelfById(int $shelfId): ?Shelf
    {
        return $this->shelfRepository->find($shelfId);
    }
}